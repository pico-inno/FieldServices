<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function currentActivityLogs()
    {
        $logs = ActivityLog::where('created_by', Auth::id())
            ->paginate();

        foreach ($logs as $log){
            $log->created_user = $log->created_user->username;
        }

        return $logs;
    }

    public function selectActivityLogs(Request $request, $id)
    {
        $logs = ActivityLog::where('created_by', $id)->paginate();

        foreach ($logs as $log) {
            $log->created_user = $log->created_user->username;
        }

        return $logs;
    }

    public function saleTransactionActivityLogs($sale_id)
    {
        $logs = ActivityLog::where('log_name', 'sale-transaction')
        ->where('properties->id', $sale_id)->get();

        foreach ($logs as $log) {
            $log->created_user = $log->created_user->username;
        }

        return $logs;
    }
}
