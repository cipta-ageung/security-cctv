<?php

namespace App\Http\Controllers;

use App\Models\AdminLogs;
use App\Models\Devices;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Occupant;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\Topics;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

use Validator;
use DB;
use Image;
use Auth;
use Log;

class NewsController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index(){

    $getData = News::select('fra_news.id','fra_news.judul','fra_news.isi_berita','fra_news.kategori','fra_news.gambar','fra_users.name')
					->join('fra_users','fra_users.id','fra_news.created_by')
					->get();

    return view('news.index', compact('getData'));
  }

  public function create(){

    return view('news.create');
  }

  public function store(Request $request){

    $message = [
      'judul.required' => 'Wajib di isi',
      'judul.unique' => 'Judul berita sudah pernah ada',
      'isi_berita.required' => 'Wajib di isi',
      'kategori.required' => 'Wajib di isi',
      'gambar.dimensions' => 'Ukuran yg di terima 1:1',
      'gambar.image' => 'Format Gambar Tidak Sesuai',
      'gambar.mimes' => 'Format Gambar yang diterima .jpg, .png, .jpeg',
      'gambar.max' => 'File Size Terlalu Besar',
    ];

    $validator = Validator::make($request->all(), [
      'judul' => 'required|unique:fra_news,judul,NULL,id,deleted_at,NULL',
      'isi_berita' => 'required',
      'kategori' => 'required',
      'gambar' => 'image|mimes:jpeg,png,jpg|max:2000|dimensions:ratio=1/1',
    ], $message);


    if($validator->fails())
    {
      return redirect()->route('news.create')->withErrors($validator)->withInput();
    }

    DB::transaction(function() use($request){
      $gambar = $request->file('gambar');

      $save = new News;

      if($gambar){
        $salt = str_random(4);
        
        $img_url = str_slug($request->judul,'-').'-apps-'.$salt. '.' . $gambar->getClientOriginalExtension();
        Image::make($gambar)->save('berita/images/'. $img_url);

        $save->gambar = $img_url;
      }

      
      $save->judul = $request->judul;
      $save->isi_berita = $request->isi_berita;
      $save->kategori = $request->kategori;
          
      $save->created_by = Auth::user()->id;
      $save->save();

      $save1 = new AdminLogs;
      $save1->user_id = Auth::user()->id;
      $save1->logs_name = 'Menambahkan Berita '.$request->judul;
      $save1->save();

      $data = $save;

      if($request->broadcast == "on"){
        $this->pushNotif($data);
      }
    });


    return redirect()->route('news.index')->with('berhasil', 'Berhasil Menambahkan Berita ');
  }


  public function edit($id){

    $find = News::find($id);

    return view('news.update', compact('find'));

  }

  public function update(Request $request){

    $find = News::findOrFail($request->id);

    $message = [
      'judul.required' => 'Wajib di isi',
      'judul.unique' => 'Judul berita sudah pernah ada',
      'isi_berita.required' => 'Wajib di isi',
      'kategori.required' => 'Wajib di isi',
      'gambar.dimensions' => 'Ukuran yg di terima 1:1',
      'gambar.image' => 'Format Gambar Tidak Sesuai',
      'gambar.mimes' => 'Format Gambar yang diterima .jpg, .png, .jpeg',
      'gambar.max' => 'File Size Terlalu Besar',
    ];

    $validator = Validator::make($request->all(), [
      'judul' => 'required|unique:fra_news,judul,'.$request->id.',id,deleted_at,NULL',
      'isi_berita' => 'required',
      'kategori' => 'required',
      'gambar' => 'image|mimes:jpeg,png,jpg|max:2000|dimensions:ratio=1/1',
    ], $message);


    if($validator->fails())
    {
      return redirect()->route('news.edit', ['id'=>$request->id])->withErrors($validator)->withInput();
    }

    DB::transaction(function() use($request){
      $gambar = $request->file('gambar');

      $update = News::find($request->id);

        if($gambar){
          $salt = str_random(4);

          $img_url = str_slug($request->judul,'-').'-aquila-land-'.$salt. '.' . $gambar->getClientOriginalExtension();
          Image::make($gambar)->save('berita/images/'. $img_url);

          $update->gambar = $img_url;
        }

      $update->judul = $request->judul;
      $update->isi_berita = $request->isi_berita;
      $update->kategori = $request->kategori;
      $update->created_by = Auth::user()->id;
      $update->update();

      $save1 = new AdminLogs;
      $save1->user_id = Auth::user()->id;
      $save1->logs_name = 'Mengubah Berita '.$request->judul;
      $save1->save();
    });

    return redirect()->route('news.index')->with('berhasil', 'Berhasil Mengubah Berita.');
  }

  public function pushNotif($data){

    $optionBuilder = new OptionsBuilder();
    $optionBuilder->setTimeToLive(60 * 20);

    $notificationBuilder = new PayloadNotificationBuilder($data->judul);
    $notificationBuilder->setBody($data->kategori)->setClickAction('OPEN_ACTIVITY_NEWS')->setSound('default');

    $dataBuilder = new PayloadDataBuilder();
    $dataBuilder->addData(['judul' => $data->judul,
                            'kategori' => $data->kategori,
                            'flag'  => 'berita',
                            'id' => $data->id,
                            'click_action'=>'OPEN_ACTIVITY_NEWS',
                        ]);

    $option = $optionBuilder->build();
    $notification = $notificationBuilder->build();
    $data = $dataBuilder->build();

    $topic = new Topics();
    $topic->topic('berita');
    
    if($data != null){
      $topicResponse = FCM::sendToTopic($topic, $option, $notification, $data);
  
      $res = [
        'numberSuccess'     => $topicResponse->isSuccess(),
        'numberFailure'     => $topicResponse->shouldRetry(),
        'tokensWithError'  => $topicResponse->error(),
      ];
      
      Log::info($res);
    }else{
      Log::info('Tidak mengirim push notif '.$data->judul);

      return 'Tidak Dapat Mengirim Push Notif';
    }

  }

  public function delete($id){

    $find = News::find($id);

    if(!$find){
      abort(404);
    }

    $judul = $find->judul;
    $find = $find->delete();

    $save1 = new AdminLogs;
    $save1->user_id = Auth::user()->id;
    $save1->logs_name = 'Menghapus data berita/news '.$judul;
    $save1->save();

    return response()->json([
      'success' => 'Berhasil',
      'message' => 'Berita/News Berhasil di Hapus',
    ]);
  }
}
