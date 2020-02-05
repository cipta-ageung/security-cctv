<?php

namespace App\Http\Controllers;

use App\Models\AdminLogs;
use App\Models\AppsLogs;
use App\Models\PanicLogs;

class LogsController extends Controller
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

    public function adminLogs(){

        $getData = AdminLogs::select('fra_admin_logs.logs_name as logs_name','fra_users.name as name','fra_admin_logs.created_at')
                            ->join('fra_users', 'fra_users.id','fra_admin_logs.user_id')
                            ->orderByDesc('fra_admin_logs.created_at')
                            ->get();

        return view('logs.admin', compact('getData'));

    }

    public function appsLogs(){

        $getData = AppsLogs::select('fra_apps_logs.logs_name as logs_name','fra_occupant.nama_kk as nama_kk','fra_apps_logs.created_at')
                            ->join('fra_occupant', 'fra_occupant.id','fra_apps_logs.occupant_id')
                            ->orderByDesc('fra_apps_logs.created_at')
                            ->get();

        return view('logs.apps', compact('getData'));
    }

    public function panicLogs(){

        $getData = PanicLogs::select('fra_panic_logs.logs_name as logs_name','fra_occupant.nama_kk as nama_kk','fra_panic_logs.created_at','fra_panic_logs.sent_to')
                            ->join('fra_occupant', 'fra_occupant.id','fra_panic_logs.user_id')
                            ->orderByDesc('fra_panic_logs.created_at')
                            ->get();

        return view('logs.panic', compact('getData'));
    }
}
