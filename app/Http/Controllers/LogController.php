<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BusinessUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class LogController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'isActive']);
    }

    public function index()
    {
        $uniqueLogNames = ActivityLog::distinct('log_name')->pluck('log_name');
        $users = BusinessUser::select('id', 'username')->get();

        return view('App.activityLog.index', [
            'uniqueLogNames' => $uniqueLogNames,
            'users' => $users
        ]);
    }



    public function activityLogsAll()
    {
        $logs = ActivityLog::query();

        foreach ($logs as $log){
            $log->created_user = $log->created_user->username;
        }

        return DataTables::of($logs)
            ->editColumn('created_user', function ($logs){
                return $logs->created_user ? $logs->created_user->username : 'Unknown';
            })
            ->rawColumns(['created_user'])
            ->make(true);
    }
    public function currentActivityLogs()
    {
        $logs = ActivityLog::where('created_by', Auth::id());

        foreach ($logs as $log){
            $log->created_user = $log->created_user->username;
        }

        return DataTables::of($logs)
            ->editColumn('created_user', function ($logs){
                return $logs->created_user->username;
            })
            ->rawColumns(['created_user'])
            ->make(true);

    }

    public function selectActivityLogs(Request $request, $id)
    {
        $logs = ActivityLog::where('created_by', $id);

        foreach ($logs as $log) {
            $log->created_user = $log->created_user->username;
        }

        return DataTables::of($logs)
            ->editColumn('created_user', function ($logs){
                return $logs->created_user->username;
            })
            ->rawColumns(['created_user'])
            ->make(true);
    }

    public function saleTransactionActivityLogs($sale_id)
    {
        $logs = ActivityLog::where('log_name', 'sale-transaction')
            ->where('properties->id', $sale_id);

        foreach ($logs as $log) {
            $log->created_user = $log->created_user->username;
        }

        return DataTables::of($logs)
            ->editColumn('created_user', function ($logs){
                return $logs->created_user->username;
            })
            ->editColumn('properties', function ($logs) {
                $data = json_decode($logs->properties);
                $status = isset($data->status) ? $data->status : null;
                $total_amount = optional($data)->total_amount;
                $payment_status = optional($data)->payment_status;

                return "
        <div class='d-flex flex-stack mb-3'>
            <div class='fw-semibold pe-10 text-gray-600'>Status:</div>
            <div class='text-end text-gray-800'>
               $status
            </div>
        </div>
                <div class='d-flex flex-stack mb-3'>
             <div class='fw-semibold pe-10 text-gray-600'>Total:</div>
            <div class='text-end   text-gray-800'>
               $total_amount
            </div>
           </div>
                   <div class='d-flex flex-stack mb-3'>
             <div class='fw-semibold pe-10 text-gray-600'>Payment Status:</div>
            <div class='text-end text-gray-800'>
               $payment_status
            </div>
        </div>
    ";
            })

            ->rawColumns(['created_user', 'properties'])
            ->make(true);
    }
}
