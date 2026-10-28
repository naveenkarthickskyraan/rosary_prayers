<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\App;
use App\Models\Module;
Use App\Models\Admin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\MappedAppCategory;
use App\Helpers\LogActivity;

use Illuminate\Database\Eloquent\Relations\HasMany;

class SampleController extends Controller
{
    public function apps_analytics(Request $request)
    {
        $get = $request->sort_direction;
        $sortColumn = "date_range_count";
        $parameter ="daterange_By=01-04-2023%20-%2030-04-2023";
        $is_android = "Android";
        $is_ios = "IOS";
        $rangedate = $request->daterange_By;

        if(!empty($rangedate)){
            $date_Arr = explode(' - ',$rangedate);
            $startDate = (date("Y-m-d", strtotime($date_Arr[0])).' 00:00:00');
            $endDate = (date("Y-m-d", strtotime($date_Arr[1])).' 23:59:00');
        }else
        {
            $startDate = \Carbon\Carbon::now()->subDays(30)->format('Y-m-d H:i:s');
            $endDate =  \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        }

        $androidcount = $androiddate = $ioscount = $iosdate = $android_arr = $ios_arr = $android_arr_count = $ios_arr_count = [];

        if(!empty($app_id)) {
            $apps = DB::table('apps')
                ->select('app_table_name', 'app_id', 'app_name', 'app_type_version', 'app_type_android', 'app_type_ios')
                ->whereIn('app_id', explode(',', $app_id))
                ->where('app_deleted', 0)
                ->limit(10000)
                ->get();
        } else {
            $apps = DB::table('apps')
                ->select('app_table_name', 'app_id', 'app_name', 'app_type_version', 'app_type_android', 'app_type_ios')
                ->where('app_deleted', 0)
                ->limit(10000)
                ->get();
        }

        $appsCount = count($apps);
        $i = 0;
        while ($i < $appsCount) {
            $app = $apps[$i];
            
            $selQry = DB::table($app->app_table_name)
                ->select(
                    DB::raw('COUNT(dev_id) as total_devices'),
                    DB::raw('SUM(CASE WHEN `created_at` LIKE ? THEN 1 ELSE 0 END) AS today_added_count'),
                    DB::raw('SUM(activity_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)) as last_7days_active_count')
                )
            ->setBindings(['%' . date('Y-m-d') . '%'])
            ->first();

            $total_devices = $selQry->total_devices;
            $today_added_count = $selQry->today_added_count;
            $active_user_last7day_count = $selQry->last_7days_active_count;
            
            $app->total_devices = $total_devices;
            $app->today_added_count = $today_added_count;
            $app->active_user_last7day_count = $active_user_last7day_count;

            if ($endDate) {
                $result = DB::table($app->app_table_name)
                            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d ") AS date'), DB::raw('COUNT(dev_id) as total_devices'))
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d ")'))
                            ->get();
            } else {
                $result = DB::table($app->app_table_name)
                    ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i:%s") AS date'), DB::raw('COUNT(dev_id) as total_devices'))
                    ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i:%s") AS date'))
                    ->get();
            }
            if(count($result) > 0){
                foreach ($result as $res) {
                    if ($app->app_type_android == $is_android && !empty($res->total_devices)) { 
                        if (isset($android_arr[$res->date])) {
                            $add = (int) $android_arr[$res->date] + (int) $res->total_devices;
                            $android_arr[$res->date] = (string) $add;
                            $android_arr_count[] = $res->total_devices;
                        } else {
                            $android_arr[$res->date] = $res->total_devices;
                            $android_arr_count[] = $res->total_devices;
                        }
                    } elseif ($app->app_type_ios == $is_ios && !empty($res->total_devices)) {
                        if (isset($ios_arr[$res->date])) {
                            $add = (int) $ios_arr[$res->date] + (int) $res->total_devices;
                            $ios_arr[$res->date] = (string) $add;
                            $ios_arr_count[] = $res->total_devices;
                        } else {
                            $ios_arr[$res->date] = $res->total_devices;
                            $ios_arr_count[] = $res->total_devices;
                        }
                    }
                }
                $app->date_range_count = $res->total_devices;
            }else{
                $app->date_range_count = 0;
            }
            $i++;
        }

        if (!empty($android_arr)) {
            ksort($android_arr);
            $androiddate = array_keys($android_arr);
            $androidcount = array_values($android_arr);
        }

        if (!empty($ios_arr)) {
            ksort($ios_arr);
            $iosdate = array_keys($ios_arr);
            $ioscount = array_values($ios_arr);
        }
        if(count($androiddate) > 0)
        {
            $data=['labels'=> $androiddate,'values'=> $androidcount ,'values_2' => $ioscount];
        }else
        {
            $data=['labels'=> $iosdate,'values'=> $androidcount ,'values_2' => $ioscount];
        }

        return view('admin.applist.apps_analytics', compact('sortColumn','apps','data','androidcount','ioscount'))->with('urlParameter', $parameter);;
    }

    public function over_all_analytics(Request $request){
        if($request->ajax())
        {
            // dd($request->limit,$request->start);
            $startDate = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d H:i:s');
            $endDate = \Carbon\Carbon::now()->endOfYear()->format('Y-m-d H:i:s');
            $is_android = "Android";
            $is_ios = "IOS";
            
            $androidcount = $androiddate = $ioscount = $iosdate = $android_arr = $ios_arr = $android_arr_count = $ios_arr_count = [];
            
            // $apps = DB::raw('SELECT * FROM `apps` ORDER BY `apps`.`app_id` ASC LIMIT $request->start,$request->limit')->get();

            $apps = DB::table('apps')
                        ->orderBy('app_id', 'ASC')
                        ->offset($request->start)
                        ->limit($request->limit)
                        ->get();
            $appsCount = count($apps);
            $i = 0;
            $appsdata = [];
            foreach($apps as $app)
            {
                $selQry = DB::table($app->app_table_name)
                    ->select(
                        DB::raw('COUNT(dev_id) as total_devices'),
                        DB::raw('SUM(CASE WHEN `created_at` LIKE "' . date('Y-m-d') . '%" THEN 1 ELSE 0 END) AS today_added_count'),
                        DB::raw('SUM(activity_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)) as last_7days_active_count')
                    )
                    ->first();

                $result = DB::table($app->app_table_name)
                    ->select(
                        DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") AS date'),
                        DB::raw('COUNT(dev_id) as total_devices')
                    )
                    ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'))
                    ->get()->toArray();

                $app->total_devices = $selQry->total_devices;

                if(!empty($result))
                {
                    $data = array(
                        'app' => $app,
                        'result' => $result,
                    );
                    $appsdata[] = $data;
                }
            }
            return response()->json(compact('appsdata'));
            // return view('admin.applist.over_all_analytics',compact('appsdata'));
        }
        $startDate = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d H:i:s');
        $endDate = \Carbon\Carbon::now()->endOfYear()->format('Y-m-d H:i:s');
        $is_android = "Android";
        $is_ios = "IOS";
        
        $androidcount = $androiddate = $ioscount = $iosdate = $android_arr = $ios_arr = $android_arr_count = $ios_arr_count = [];
        
        $apps = DB::table('apps')
                    ->select('app_table_name', 'app_id', 'app_name', 'app_type_version', 'app_type_android', 'app_type_ios')
                    ->where('app_deleted', 0)
                    ->limit(10)
                    ->get();

        $appsCount = count($apps);
        $i = 0;
        $appsdata = [];
        foreach($apps as $app)
        {
            $selQry = DB::table($app->app_table_name)
                ->select(
                    DB::raw('COUNT(dev_id) as total_devices'),
                    DB::raw('SUM(CASE WHEN `created_at` LIKE "' . date('Y-m-d') . '%" THEN 1 ELSE 0 END) AS today_added_count'),
                    DB::raw('SUM(activity_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)) as last_7days_active_count')
                )
                ->first();

            $result = DB::table($app->app_table_name)
                ->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") AS date'),
                    DB::raw('COUNT(dev_id) as total_devices')
                )
                ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'))
                ->get()->toArray();

            $app->total_devices = $selQry->total_devices;

            if(!empty($result))
            {
                $data = array(
                    'app' => $app,
                    'result' => $result,
                );
                $appsdata[] = $data;
            }
        }

        return view('admin.applist.over_all_analytics',compact('appsdata'));

    }

    public function aesc(Request $request,$id)
    {
        $startDate = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d H:i:s');
        $endDate = \Carbon\Carbon::now()->endOfYear()->format('Y-m-d H:i:s');
        $is_android = "Android";
        $is_ios = "IOS";
        
        $androidcount = $androiddate = $ioscount = $iosdate = $android_arr = $ios_arr = $android_arr_count = $ios_arr_count = [];
        
        $app = DB::table('apps')
                ->select('app_table_name', 'app_id', 'app_name', 'app_type_version', 'app_type_android', 'app_type_ios')
                ->where('app_id', $id)
                ->where('app_deleted', 0)
                ->first();
        
        $selQry = DB::table($app->app_table_name)
                    ->select(
                        DB::raw('COUNT(dev_id) as total_devices'),
                        DB::raw('SUM(CASE WHEN `created_at` LIKE "' . date('Y-m-d') . '%" THEN 1 ELSE 0 END) AS today_added_count'),
                        DB::raw('SUM(activity_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)) as last_7days_active_count')
                    )
                    ->first();

        $total_devices = $selQry->total_devices;
        $today_added_count = $selQry->today_added_count;
        $active_user_last7day_count = $selQry->last_7days_active_count;

        //Total Devices count list
        $result = DB::table($app->app_table_name)
                    ->select(
                        DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d ") AS date'),
                        DB::raw('COUNT(dev_id) as total_devices')
                    )
                    ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d ")'))
                    ->get()
                    ->toArray();

        $result = collect($result);

        // COuntry Chart Datas List By Users
        $country = DB::table($app->app_table_name)
                    ->select(
                        DB::raw('IF(country_code = "", "other", country_code) AS name'),
                        DB::raw('COUNT(dev_id) as y')
                    )
                    ->groupBy('name')
                    ->get()
                    ->toArray();
        
        //App Device Data get the code list of Users 
        $appDevice = DB::table($app->app_table_name)
                    ->select(
                        DB::raw('appDevice AS device'),
                        DB::raw('COUNT(dev_id) as count')
                    )
                    ->groupBy('device')
                    ->get()
                    ->toArray();

        // Device Data get the code list of Users 
        $device = DB::table($app->app_table_name)
                    ->select(
                        DB::raw('IF(devicename = "", "All Andriod Users",devicename) AS name'),
                        DB::raw('COUNT(dev_id) as count')
                    )
                    ->groupBy('name')
                    ->get()
                    ->toArray();

        //Combine foe color and Seleected True statement for List 
        $combinedDataWithColor = array_map(function ($item) {
            $color = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
            $list = DB::table('country')->where('iso',$item->name)->first();
            if($list != null)
            {
                $item->name = $item->name.' - '.$list->name ?? 'other';
            }
            // $item->color = $color;
            if ($item->name == "IN - INDIA") {
                $item->sliced = true;
                $item->selected = true;
            }
            return $item;
        }, $country);


        $android_arr = $result
            ->when($app->app_type_android == $is_android, function ($collection) {
                return $collection->filter(function ($item) {
                    return !empty($item->total_devices);
                });
            })
            ->pluck('total_devices', 'date')
            ->toArray();

        $ios_arr = $result
            ->when($app->app_type_ios == $is_ios, function ($collection) {
                return $collection->filter(function ($item) {
                    return !empty($item->total_devices);
                });
            })
            ->pluck('total_devices', 'date')
            ->toArray();
        $app->date_range_count = $result->isEmpty() ? 0 : $result->last()->total_devices;
                    
        $data = [];

        if (!empty($android_arr)) {
            ksort($android_arr);
            $data['labels'] = array_keys($android_arr);
            $data['values'] = array_values($android_arr);
            $data['values_2'] = $total_devices;
        } elseif (!empty($ios_arr)) {
            ksort($ios_arr);
            $data['labels'] = array_keys($ios_arr);
            $data['values'] = array_values($ios_arr);
            $data['values_2'] = $total_devices;
        } else {
            $data['labels'] = [];
            $data['values'] = [];
            $data['values_2'] = [];
        }

        // dd($app);

        return view('admin.applist.details_analytics',compact('data','app','combinedDataWithColor','appDevice','device'));
    }

    public function ajax_call_chart(Request $request){
        DD('TEST');
        $rangedate = $request->daterange_By;

        $is_android = "Android";
        $is_ios = "IOS";
        if(!empty($rangedate)){
            $date_Arr = explode(' - ',$rangedate);
            $startDate = (date("Y-m-d", strtotime($date_Arr[0])).' 00:00:00');
            $endDate = (date("Y-m-d", strtotime($date_Arr[1])).' 23:59:00');
        }else
        {
            $startDate = \Carbon\Carbon::now()->subDays(30)->format('Y-m-d H:i:s');
            $endDate =  \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        }       

        $androidcount = $androiddate = $ioscount = $iosdate = $android_arr = $ios_arr = [];
        $apps =App::select('app_table_name', 'app_id', 'app_name', 'app_type_version', 'app_type_android', 'app_type_ios')
            ->where('app_deleted', 0)
            ->get();

        $appsCount = count($apps);
        $i = 0;
        while ($i < $appsCount) {
            $app = $apps[$i];

            if ($endDate) {
                
                $result = DB::table($app->app_table_name)
                            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d ") AS date'), DB::raw('COUNT(dev_id) as total_devices'))
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d ")'))
                            ->get();
            } else {
                $result = DB::table($app->app_table_name)
                    ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i:%s") AS date'), DB::raw('COUNT(dev_id) as total_devices'))
                    ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i:%s") AS date'))
                    ->get();
            }
            if(count($result) > 0){
                foreach ($result as $res) { 
                    if ($app->app_type_android == $is_android && !empty($res->total_devices)) { 
                        if (isset($android_arr[$res->date])) {
                            $add = (int) $android_arr[$res->date] + (int) $res->total_devices;
                            $android_arr[$res->date] = (string) $add;
                        } else {
                            $android_arr[$res->date] = $res->total_devices;
                        }
                    } elseif ($app->app_type_ios == $is_ios && !empty($res->total_devices)) {
                        if (isset($ios_arr[$res->date])) {
                            $add = (int) $ios_arr[$res->date] + (int) $res->total_devices;
                            $ios_arr[$res->date] = (string) $add;
                        } else {
                            $ios_arr[$res->date] = $res->total_devices;
                        }
                    }
                }
            }
            $i++;
        }

        if (!empty($android_arr)) {
            ksort($android_arr);
            $androiddate = array_keys($android_arr);
            $androidcount = array_values($android_arr);
        }

        if (!empty($ios_arr)) {
            ksort($ios_arr);
            $iosdate = array_keys($ios_arr);
            $ioscount = array_values($ios_arr);
        }

        if(count($androiddate) > 0)
        {
            $data=['labels'=> $androiddate,'values'=> $androidcount ,'values_2' => $ioscount];
        }else
        {
            $data=['labels'=> $iosdate,'values'=> $androidcount ,'values_2' => $ioscount];
        }

        $androidcount = array_sum($androidcount);
        $ioscount = array_sum($ioscount);
        return response()->json(compact('data','androidcount','ioscount'));
    }  

    public function datatable_analytics(Request $request)
    {   
        $rangedate = $request->daterange_By;

        if(!empty($rangedate)){
            $date_Arr = explode(' - ',$rangedate);
            $startDate = (date("Y-m-d", strtotime($date_Arr[0])).' 00:00:00');
            $endDate = (date("Y-m-d", strtotime($date_Arr[1])).' 23:59:00');
        }else
        {
            $startDate = \Carbon\Carbon::now()->subDays(30)->format('Y-m-d H:i:s');
            $endDate =  \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        }       

        $apps = App::select('app_table_name', 'app_id', 'app_name', 'app_type_version', 'app_type_android', 'app_type_ios')
            ->where('app_deleted', 0)
            ->get();

        $apps->each(function ($app) use ($startDate, $endDate) {
            $endDateCondition = $endDate ? ['created_at', '>=', $startDate, 'created_at', '<=', $endDate] : [];

            $selQry = DB::table($app->app_table_name)
                ->select(
                    DB::raw('COUNT(dev_id) as total_devices'),
                    DB::raw('SUM(CASE WHEN `created_at` LIKE ? THEN 1 ELSE 0 END) AS today_added_count'),
                    DB::raw('SUM(activity_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)) as last_7days_active_count')
                )
                ->setBindings(['%' . date('Y-m-d') . '%'])
                ->first();

            $app->total_devices = $selQry->total_devices;

            $result = DB::table($app->app_table_name)
                ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d ") AS date'), DB::raw('COUNT(dev_id) as total_devices'))
                ->when(!empty($endDate), function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('created_at', [$startDate, $endDate]);
                })
                ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d ")'))
                ->get();

            $app->date_range_count = array_sum($result->pluck('total_devices')->keyBy('date')->toArray()) ?? 0;
        });

        return response()->json(compact('apps'));
    }

    public function fileExport(Request $request)
    {
        return Excel::download(new ExportApp, 'App.xlsx');
    }
    public function fileImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xls|max:2048',
        ]);

        Excel::import(new ImportApp, $request->file);
        return back()->with('message','Record Created Successfully');
    }

    public function show(Request $request)
    {
        // LogActivity::addToLog();
        $role = Auth::guard('admin')->user()->role;
        $AccessPrivilege = Auth::guard('admin')->user()->accountAccessPrivilege;
        $AccessModule = Auth::guard('admin')->user()->accountAccessModule;
        $explode = explode('|',$AccessModule);
        $explode_1 = explode('|',$AccessPrivilege);
        $module = Module::Select(['module_name'])->whereIn('module_name',$explode)->get();

        $permission=[];
        foreach($module as $row)
        {
            $permission[] = $row->module_name;
        }
        $permis = $permission;
        $array = array_intersect($explode, $permis);
        $array_1= in_array('view',$explode_1);
        
        if(($role == "SuperAdmin") || ($array == true &&  $array_1 == true))
        {
            $search = $request->searchKey;
            $action = $request->action;
            $page = $request->page;
            if($request->doAction == 'enable')
            {
                foreach($request->galleryIds as $row)
                {
                    $disable = App::find($row);
                    $disable->is_active = 0;
                    $disable->save();
                }
                return back()->with('message','Record Enable Successfully');

            }
            else if($request->doAction == 'disable')
            {
                foreach($request->galleryIds as $row)
                {
                    $disable = App::find($row);
                    $disable->is_active = "1";
                    $disable->save();
                }
                return back()->with('message','Record Disabled Successfully');
            }  
            else if($request->doAction == 'delete')
            {
                foreach($request->galleryIds as $row)
                {
                    $disable = App::find($row);
                    $disable->app_deleted = 1;
                    $disable->save();
                }
                return back()->with('message','Record Delete Successfully');
            } 
            else if($search)
            {
                if(!empty($request->searchBy) && $request->searchStatus != null)
                {
                    $searchBy = $request->searchBy;
                    $searchStatus = $request->searchStatus;
                    $apps = App::orderBy('app_id','desc')->where('app_deleted','0')->where('is_active',$request->searchStatus)->Where("app_name", 'like', '%' . $search . '%')->paginate($action);
                    return view('admin.applist.app_list', compact('search','apps','action','searchStatus','searchBy'));
                }
                else
                {
                    $searchBy = $request->searchBy ?? '';
                    $searchStatus = $request->searchStatus ?? '';
                    $apps = App::orderBy('app_id','desc')->where('app_deleted','0')->Where("app_name", 'like', '%' . $search . '%')->orWhere("app_description", 'like', '%' . $search . '%')->paginate($action);
                    return view('admin.applist.app_list', compact('search','apps','action','searchStatus','searchBy'));
                }
            }
            else if($action != null)
            {
                $category = App::orderBy('app_id','desc')->where('app_deleted','0')->paginate($action);
                return view('admin.applist.app_list', ['apps' => $category,'action' => $action]);
            }
            else
            {
                $category = App::orderBy('app_id','desc')->where('app_deleted','0')->paginate(10);
                return view('admin.applist.app_list', ['apps' => $category]);
            }
        }
        else
        {
            return redirect()->route('admin/dashboard')->with("error","Access Denied");
        }
    }

    function Add_list()
    {
        // LogActivity::addToLog();
        $role = Auth::guard('admin')->user()->role;
        $AccessPrivilege = Auth::guard('admin')->user()->accountAccessPrivilege;
        $AccessModule = Auth::guard('admin')->user()->accountAccessModule;
        $explode = explode('|',$AccessModule);
        $explode_1 = explode('|',$AccessPrivilege);
        $module = Module::Select(['module_name'])->whereIn('module_name',$explode)->get();
        $permission=[];
        foreach($module as $row)
        {
            $permission[] = $row->module_name;
        }
        $permis = $permission;
        $array = array_intersect($explode, $permis);
        $array_1= in_array('add',$explode_1);
        if(($role == "SuperAdmin") || ($array  && $array_1== true))
        {
            return view('admin.applist.add_app');
        }
        else
        {
            return redirect()->route('admin/dashboard')->with("error","Access Denied");
        }
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_description' => 'required',
        ]);

        $user = new App;
        $user->app_name = $request['app_name'];
        $user->app_description = $request['app_description'];
        $user->app_image = $request['file'];

        if($request->file_type == 1)
        {
            $user->image_type = $request->file_type;
            $user->app_image = $request['file'];
            if($request->hasFile('file'))
            { 
                $image = $request['file'];
                $image_name = $image->getClientOriginalName();
                $extension = pathinfo($image_name, PATHINFO_EXTENSION);
                $encrpt = round(microtime(true)*1000);
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $charactersLength; $i++) 
                {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                $new_name = ($encrpt).'_'.$randomString.'.'.$extension;
                $image->move(public_path('calendar_images'),$new_name);
                $new_name_3 = 'calendar_images/'.$new_name;
                $uploaded_file = $new_name_3 ?? '';   
                $user->app_image = $uploaded_file;
            }
        }
        else if($request->file_type == 2)
        {
            $user->app_image = $request->url_image;
            $user->image_type = $request->file_type;
        }
        LogActivity::addToLog();
        $user->save();
        return back()->with('message','App Created Successfully');
    }

    public function edit($id)
    {
        LogActivity::addToLog();
        $role = Auth::guard('admin')->user()->role;
        $AccessPrivilege = Auth::guard('admin')->user()->accountAccessPrivilege;
        $AccessModule = Auth::guard('admin')->user()->accountAccessModule;
        $explode = explode('|',$AccessModule);
        $explode_1 = explode('|',$AccessPrivilege);
        $module = Module::Select(['module_name'])->whereIn('module_name',$explode)->get();
        $permission=[];
        foreach($module as $row)
        {
            $permission[] = $row->module_name;
        }
        $permis = $permission;
        $array = array_intersect($explode, $permis);
        $array_1= in_array('edit',$explode_1);
        if(($role == "SuperAdmin") || ($array  && $array_1== true))
        {
            $edit = App::find($id);
            $category = Category::where('app_deleted','0')->get();
            $mapped_res = MappedAppCategory::select(['category_id'])->where('app_id',$id)->get();
            $mapped_app=[];
            foreach($mapped_res as $row)
            {
                    $mapped_app[] = $row->category_id;
            }
            return view('admin.applist.mapped_app_category', ['edit'=> $edit,'category_list'=>$category,'mapped_app'=>$mapped_app]);
        }
        else
        {
            return redirect()->route('admin.dashboard')->with("error","Access Denied");
        }

    }

    public function active(Request $request)
    {
        // LogActivity::addToLog();
        $role = Auth::guard('admin')->user()->role;
        $AccessPrivilege = Auth::guard('admin')->user()->accountAccessPrivilege;
        $AccessModule = Auth::guard('admin')->user()->accountAccessModule;
        $explode = explode('|',$AccessModule);
        $explode_1 = explode('|',$AccessPrivilege);
        $module = Module::Select(['module_name'])->whereIn('module_name',$explode)->get();
        $permission=[];
        foreach($module as $row)
        {
            $permission[] = $row->module_name;
        }
        $permis = $permission;
        $array = array_intersect($explode, $permis);
        $array_1= in_array('edit',$explode_1);
        if(($role == "SuperAdmin") || ($array  && $array_1== true))
        {
            $parent_id = $request->app_id;
            $status = $request->status;
            if($status == 0)
            {
                $app_active = App::find($parent_id);
                $app_active->is_active ='1';
                $app_active->save();
                return back()->with('message','App Disabled Successfully');
            }
            if($status == 1)
            {
                $app_active = App::find($parent_id);
                $app_active->is_active ='0';
                $app_active->save();
                return back()->with('message','App Enabled Successfully');
            }
        }
        else
        {
            return redirect()->route('admin.dashboard')->with("error","Access Denied");
        }
        
    }

    public function delete(Request $request, $id)
    {
        LogActivity::addToLog();
        $role = Auth::guard('admin')->user()->role;
        $AccessPrivilege = Auth::guard('admin')->user()->accountAccessPrivilege;
        $AccessModule = Auth::guard('admin')->user()->accountAccessModule;
        $explode = explode('|',$AccessModule);
        $explode_1 = explode('|',$AccessPrivilege);
        $module = Module::Select(['module_name'])->whereIn('module_name',$explode)->get();
        $permission=[];
        foreach($module as $row)
        {
            $permission[] = $row->module_name;
        }
        $permis = $permission;
        $array = array_intersect($explode, $permis);
        $array_1= in_array('delete',$explode_1);
        if(($role == "SuperAdmin") || ($array  && $array_1== true))
        {
            $Q1 = App::find($id);
            $Q1->app_deleted = '1';
            $Q1->save();
            return back()->with('message','Delete Record Successfully');
        }
        else
        {
            return redirect()->route('admin.dashboard')->with("error","Access Denied");
        }
    }

    public function EditApp($id)
    {
        // LogActivity::addToLog();
        $role = Auth::guard('admin')->user()->role;
        $AccessPrivilege = Auth::guard('admin')->user()->accountAccessPrivilege;
        $AccessModule = Auth::guard('admin')->user()->accountAccessModule;
        $explode = explode('|',$AccessModule);
        $explode_1 = explode('|',$AccessPrivilege);
        $module = Module::Select(['module_name'])->whereIn('module_name',$explode)->get();
        $permission=[];
        foreach($module as $row)
        {
            $permission[] = $row->module_name;
        }
        $permis = $permission;
        $array = array_intersect($explode, $permis);
        $array_1= in_array('edit',$explode_1);
        if(($role == "SuperAdmin") || ($array  && $array_1== true))
        {
            $edit = App::find($id);
            $array = array($edit);
            return view('admin.applist.edit_app_list', ['edit_app_id'=> $array]);
        }
        else
        {
            return redirect()->route('admin.dashboard')->with("error","Access Denied");
        }
    }
    
    public function EditAppList(Request $request, $id)
    {
        
        $request->validate([
            'edited_app_name' => 'required|string|max:255',
            'edited_app_description' => 'required',
        ]);
        $app_version = $request->hidden;
        $app = App::all();
        foreach($app as $row)
        {
            $app_name = $row->app_name;
            $app_description = $row->app_description;
            $app_image = $row->app_image;
            $old_version = $row->app_version;
        }
        $user = AppList::find($id);
        // dd($user);
        $user->app_version = $app_version + 1;
        $user->app_name = $request->input('edited_app_name');
        $user->app_version = $app_version + 1;
        $user->app_description = $request->input('edited_app_description');
        if($request->file_type == 1)
        {
            $user->image_type = $request->file_type;
            if($request->hasFile('file'))
            { 
                $image = $request['file'];
                $image_name = $image->getClientOriginalName();
                $extension = pathinfo($image_name, PATHINFO_EXTENSION);
                $encrpt = round(microtime(true)*1000);
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $charactersLength; $i++) 
                {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                $new_name = ($encrpt).'_'.$randomString.'.'.$extension;
                $image->move(public_path('calendar_images'),$new_name);
                $new_name_3 = 'calendar_images/'.$new_name;
                $uploaded_file = $new_name_3 ?? '';   
                $user->app_image = $uploaded_file;
            }
        }
        else if($request->file_type == 2)
        {
                $user->app_image = $request->url_image;
                $user->image_type = $request->file_type;
        }
        LogActivity::addToLog();
        $user->save();
        return back()->with('message','App Edit Successfully');
    }
}
