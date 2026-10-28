<?php

namespace App\Http\Controllers;

use App\Models\AppList;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function Dashboard()
    {
        LogActivity::addToLog();
        $app = AppList::orderBy('id','asc')->where('deleted_at','0')->get();
        $active = AppList::where('is_active','1')->get();
        return view('admin.applist.app_list', ['apps' => $app],['app_active'=>$active]);
    }
}
