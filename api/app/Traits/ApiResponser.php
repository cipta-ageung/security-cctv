<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;

use Validator;

trait ApiResponser{

    protected $request;

    protected $jwt;

    public function __construct(JWTAuth $jwt, Request $request)
    {
        $this->jwt = $jwt;
        $this->request = $request;
    }

    protected function trueResponse($message='', $code=200, $data = null){
        if($data !== null){
            return response()->json(['code' => $code, 'description' => $message, 'results' => $data],$code,[], JSON_UNESCAPED_SLASHES);
        }else{
            return response()->json(['code' => $code, 'description' => $message],$code);
        }
    }

    protected function responseWithToken($message='', $code=200, $data = null){
        $token = $this->jwt->refresh();

        if($data !== null){
            return response()->json(['code' => $code, 'description' => $message, 'access_token' => $token, 'results' => $data], $code, [], JSON_UNESCAPED_SLASHES);
        }else{
            return response()->json(['code' => $code, 'description' => $message, 'access_token' => $token],$code);
        }
    }

    protected function paginate(Collection $collection, Request $request)
    {

        $validator= Validator::make($request->all(),[
            'perpage'   => 'numeric'
        ]);

        if($validator->fails()){
            $data = array();
            foreach($validator->messages()->getMessages() as $field_name => $values){
                $data[$field_name]=$values[0];
            }
            return $this->trueResponse('validasi gagal',400,$data);
        }

        $page = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 8;
        if($request->has('perpage')){
            $perPage = (int) $request->perpage;
        }

        $page = LengthAwarePaginator::resolveCurrentPage();

        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        return $paginated->appends(Input::except('page'));
    }  

    protected function showAll($msg, Collection $collection, Request $request, $code=200)
    {   
        if($collection->isEmpty()){
            $collection = $this->paginate($collection, $request);
            return response()->json(['code' => $code, 'description' => $msg, 'results' => $collection], $code, [], JSON_UNESCAPED_SLASHES);
        }
        
        $collection = $this->sortData(collect($collection), $request);
        $collection = $this->paginate(collect($collection), $request);
        
        return response()->json(['code' => $code, 'description' => $msg, 'results' => $collection], $code, [], JSON_UNESCAPED_SLASHES);
    }


    // Show many data collection
    protected function showAllWithToken($msg, Collection $collection, Request $request, $code=200)
    {
        $token = $this->jwt->refresh();
        
        if($collection->isEmpty()){
            $collection = $this->paginate($collection, $request);
            return response()->json(['code' => $code, 'description' => $msg, 'access_token' => $token, 'results' => $collection], $code, [], JSON_UNESCAPED_SLASHES);
        }
        
         $collection = $this->sortData(collect($collection), $request);
         $collection = $this->paginate(collect($collection), $request);
        return response()->json(['code' => $code, 'description' => $msg, 'access_token' => $token, 'results' => $collection], $code, [], JSON_UNESCAPED_SLASHES);
    }

    // Showing one data
    protected function showOne(Model $model, $code = 200)
    {
        return $this->successResponse(['aquilaland' => ['status' => ['code' => $code, 'description' => 'ok'], 'results' => $model]], $code);
    }

    // Filtering list data
    protected function filterData(Collection $collection)
    {
        foreach($this->request->query() as $query => $value){

            if(isset($query, $value)){
                $collection = $collection->where($query, $value);
            }
        }

        return $collection;
    }

    // Sorting list data
    protected function sortData(Collection $collection, Request $request)
    {
        $orderBy = strtoupper($request->order) ? : 'DESC';

        if($request->has('field') && $orderBy){
            $attribute = $request->field;
            
            if($orderBy == 'DESC'){
                $collection = $collection->sortByDesc($attribute);
            }else{
                $collection = $collection->sortBy($attribute);
            }
            
        }
        return $collection;
    }

    protected function cacheResponse($data)
    {
        $url = $this->request->url();
        $queryParams = $this->request->query();

        ksort($queryParams);

        $queryString = http_build_query($queryParams);
        $fullUrl = "{url}?{$queryString}";

        return Cache::remember($fullUrl, 20/60, function() use($data){
            return $data;
        });
    }

}