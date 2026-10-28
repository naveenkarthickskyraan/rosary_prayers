<?php

namespace App\Helpers;
use Illuminate\Http\Request;
use Auth;
use App\Models\LogActivity as LogActivityModel;


class LogActivity 
{

    public static function addToLog()
    {
		$log = [];
    	$log['subject'] = "Activity Log";
    	$log['url'] = request()->url();
    	$log['method'] = request()->method();
    	$log['ip'] = request()->ip();
    	$log['agent'] = request()->header('user-agent');
    	$log['user_id'] = Auth::guard('admin')->user()->id;
		$log['email'] = Auth::guard('admin')->user()->email;
		$log['role'] = Auth::guard('admin')->user()->role;
		$log['time'] = time();
    	LogActivityModel::create($log);
    }


    public static function logActivityLists()
    {
    	return LogActivityModel::latest()->get();
    }


}