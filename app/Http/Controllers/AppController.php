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
use App\Models\iosKeyId;
use App\Models\iosTeamId;
use App\Models\AppsAudioManager;
use App\Models\AppTextToSpeechManager;
use App\Models\ImageAppList;
use App\Models\Version;
use App\Models\AppList;
use App\Models\VideoAppList;
use App\Models\QuotesAppList;
use App\Models\Language;
use Illuminate\Support\Facades\Storage;
use App\Imports\AppImport;
use App\DataTables\AppDataTable;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppController extends Controller
{
    public function index(AppDataTable $dataTable){
        return $dataTable->render('admin.applist.users');
    }

    public function apps_analytics(Request $request){
        $rangedate = $request->daterange_By;

        $is_android = "Android";
        $is_ios = "IOS";

        if (!empty($rangedate)) {
            $date_Arr = explode(' - ', $rangedate);
            if($date_Arr[0] == $date_Arr[1])
            {
                $startDate = (date("Y-m-d", strtotime($date_Arr[0])) . ' 00:00:00');
                $endDate = '';
            }else{
                $startDate = (date("Y-m-d", strtotime($date_Arr[0])) . ' 00:00:00');
                $endDate = (date("Y-m-d", strtotime($date_Arr[1])) . ' 23:59:59');
            }
        } else {
            $startDate = \Carbon\Carbon::now()->subDays(30)->format('Y-m-d H:i:s');
            $endDate = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        }

        $androidcount = $androiddate = $ioscount = $iosdate = $android_arr = $ios_arr = [];
        // $apps = App::select('app_table_name', 'app_id', 'app_name', 'app_type_version', 'app_type_android', 'app_type_ios')
        //             ->where('app_deleted', 0)
        //             ->get();

        // $appsCount = count($apps);
        // $i = 0;
        // $array_android_count = 0;
        // $array_ios_count = 0;

        // while ($i < $appsCount) {
        //     $app = $apps[$i];
        //     $selQry = DB::table($app->app_table_name)
        //                 ->select(
        //                     DB::raw('COUNT(dev_id) as total_devices'),
        //                     DB::raw('SUM(CASE WHEN `created_at` LIKE ? THEN 1 ELSE 0 END) AS today_added_count'),
        //                     DB::raw('SUM(activity_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)) as last_7days_active_count')
        //                 )
        //                 ->setBindings(['%' . date('Y-m-d') . '%'])
        //                 ->first();
        //     $app->total_devices = $selQry->total_devices;
        //     if($endDate == '')
        //     {
        //         $query = "
        //                 SELECT DATE_FORMAT(created_at, '%Y-%m-%d') AS date, COUNT(dev_id) AS total_devices
        //                 FROM {$app->app_table_name}
        //                 WHERE created_at >= '{$startDate}'
        //                 GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d')
        //             ";

        //         $result = DB::select($query);
        //     }else{
        //         $query = "
        //             SELECT DATE_FORMAT(created_at, '%Y-%m-%d') AS date, COUNT(dev_id) AS total_devices
        //             FROM $app->app_table_name
        //             WHERE created_at BETWEEN ? AND ?
        //             GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d')
        //         ";

        //         $result = DB::select($query, [$startDate, $endDate]);
        //     }
        //     $sumTotalDevices = 0;
        //     $totalSum = 0;
        //     if(count($result) > 0){
        //         foreach ($result as $res) {
        //             if ($app->app_type_android == $is_android && !empty($res->total_devices)) {
        //                 if (isset($android_arr[$res->date])) {
        //                     $add = (int) $android_arr[$res->date] + (int) $res->total_devices;
        //                     $android_arr[$res->date] = (string) $add;
        //                     $array_android_count = array_sum($android_arr);
        //                 } else {
        //                     $android_arr[$res->date] = (string) $res->total_devices;
        //                     $array_android_count = array_sum($android_arr);
        //                 }
        //             } elseif ($app->app_type_ios == $is_ios && !empty($res->total_devices)) {
        //                 if (isset($ios_arr[$res->date])) {
        //                     $add = (int) $ios_arr[$res->date] + (int) $res->total_devices;
        //                     $ios_arr[$res->date] = (string) $add;
        //                     $array_ios_count = array_sum($ios_arr);
        //                 } else {
        //                     $ios_arr[$res->date] = (string) $res->total_devices;
        //                     $array_ios_count = array_sum($ios_arr);
        //                 }
        //             }
        //             $sumTotalDevices += $res->total_devices;
        //             $totalSum += $res->total_devices;
        //         }
        //         $app->date_range_count = $totalSum;
        //     }else{
        //         $app->date_range_count = $sumTotalDevices;
        //     }
        //     $i++;
        // }
        // // dd($array_android_count,$array_ios_count);


        // if (!empty($android_arr)) {
        //     ksort($android_arr);
        //     $androiddate = array_keys($android_arr);
        //     $androidcount = array_values($android_arr);
        // }
        // // dd($array_android_count,$array_ios_count);
        // if (!empty($ios_arr)) {
        //     ksort($ios_arr);
        //     $iosdate = array_keys($ios_arr);
        //     $ioscount = array_values($ios_arr);
        // }

        // if(count($androiddate) > 0)
        // {
        //     $data=['labels'=> $androiddate,'values'=> $androidcount ,'values_2' => $ioscount];
        // }else
        // {
        //     $data=['labels'=> $iosdate,'values'=> $androidcount ,'values_2' => $ioscount];
        // }

        // $androidcount = $android_arr;
        // $ioscount = $ios_arr;

        $apps = [];
        $data=['labels'=> $androiddate,'values'=> $androidcount ,'values_2' => $ioscount];

        return view('admin.applist.apps_analytics', compact('apps','data','androidcount','ioscount'));
    }

    public function over_all_analytics(Request $request){
        if($request->ajax())
        {
            if(isset($request->daterange_By) && !empty($request->daterange_By))
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

                $is_android = "Android";
                $is_ios = "IOS";

                $apps = DB::table('apps')
                            ->orderBy('app_id', 'ASC')
                            ->offset($request->start)
                            ->limit($request->limit)
                            ->get();

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
                        )->whereBetween('created_at', [$startDate, $endDate])
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

            }
            
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

    public function aesc(Request $request,$id){
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

        $apps = DB::table('apps')
                ->select('app_table_name', 'app_id', 'app_name', 'app_type_version', 'app_type_android', 'app_type_ios')
                ->where('app_deleted', 0)
                ->get();


        foreach($apps as $apprw)
        {
            $selQrye = DB::table($apprw->app_table_name)
                ->select(
                    DB::raw('COUNT(dev_id) as total_devices'),
                    DB::raw('SUM(CASE WHEN `created_at` LIKE "' . date('Y-m-d') . '%" THEN 1 ELSE 0 END) AS today_added_count'),
                    DB::raw('SUM(activity_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)) as last_7days_active_count')
                )
                ->first();

            $apprw->total_devices = $selQrye->total_devices ?? 0;
        }
        $apps = $apps->sortByDesc('total_devices');

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

        // seven_days count list
        $seven_days = DB::table($app->app_table_name)
                        ->select(
                            DB::raw('DATE_FORMAT(activity_time, "%Y-%m-%d") AS date'),
                            DB::raw('COUNT(dev_id) as total_devices')
                        )
                        ->where('activity_time', '>=', \Carbon\Carbon::now()->subDays(7)->startOfDay())
                        ->groupBy(DB::raw('DATE_FORMAT(activity_time, "%Y-%m-%d")'))
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

        return view('admin.applist.details_analytics',compact('apps','data','app','combinedDataWithColor','appDevice','device','seven_days'));
    }

    public function ajax_call_chart(Request $request){

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

    public function datatable_analytics(Request $request){
        if (isset($request->daterange_By) && !empty($request->daterange_By)) {
            $rangedate = $request->daterange_By;
            if (!empty($rangedate)) {
                $date_Arr = explode(' - ', $rangedate);
                if($date_Arr[0] == $date_Arr[1])
                {
                    $startDate = (date("Y-m-d", strtotime($date_Arr[0])) . ' 00:00:00');
                    $endDate = '';
                }else{
                    $startDate = (date("Y-m-d", strtotime($date_Arr[0])) . ' 00:00:00');
                    $endDate = (date("Y-m-d", strtotime($date_Arr[1])) . ' 23:59:59');
                }
            } else {
                $startDate = \Carbon\Carbon::now()->subDays(30)->format('Y-m-d H:i:s');
                $endDate = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
            }
            $is_android = "Android";
            $is_ios = "IOS";

            $appsQuery = DB::table('apps')->orderBy('app_id', 'ASC');

            if ($request->has('start') && $request->has('limit') && $request->start!=0 || $request->limit!=0) {
                $apps = $appsQuery
                    ->offset($request->start)
                    ->limit($request->limit)
                    ->get();
            } else {
                $apps = $appsQuery->get();
            }

            $appsdata = [];
            foreach ($apps as $app) {
                $selQry = DB::table($app->app_table_name)
                    ->select(
                        DB::raw('COUNT(dev_id) as total_devices'),
                        DB::raw('SUM(CASE WHEN `created_at` LIKE "' . date('Y-m-d') . '%" THEN 1 ELSE 0 END) AS today_added_count'),
                        DB::raw('SUM(activity_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)) as last_7days_active_count')
                    )
                    ->first();

                if($endDate == '')
                {
                    $query = "
                            SELECT DATE_FORMAT(created_at, '%Y-%m-%d %h:%i') AS date, COUNT(dev_id) AS total_devices
                            FROM {$app->app_table_name}
                            WHERE created_at >= '{$startDate}'
                            GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d %h:%i')
                        ";

                    $result = DB::select($query);
                }else{
                    $query = "
                        SELECT DATE_FORMAT(created_at, '%Y-%m-%d') AS date, COUNT(dev_id) AS total_devices
                        FROM $app->app_table_name
                        WHERE created_at BETWEEN ? AND ?
                        GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d')
                    ";

                    $result = DB::select($query, [$startDate, $endDate]);
                }
                $app->total_devices = $selQry->total_devices;

                $sumTotalDevices = 0;
                foreach ($result as $row) {
                    $sumTotalDevices += $row->total_devices;
                }
                $app->date_range_count = $sumTotalDevices;
                if (!empty($result)) {
                    $data = array(
                        'app' =>(array) $app,
                        'result' => $result,
                        'total'=>$sumTotalDevices
                    );
                    $appsdata[] = $data;
                }
            }

            return response()->json(compact('appsdata'));
        }
    }

    public function fileExport(Request $request){
        return Excel::download(new ExportApp, 'App.xlsx');
    }

    public function fileImport(Request $request){
        $request->validate([
            'file' => 'required|mimes:csv,xls|max:2048',
        ]);

        Excel::import(new ImportApp, $request->file);
        return back()->with('message','Record Created Successfully');
    }

    public function show(Request $request){
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
                    $apps = App::sortable(['app_id','desc'])->where('app_deleted','0')->where('is_active',$request->searchStatus)->Where("app_name", 'like', '%' . $search . '%')->paginate($action);
                    return view('admin.applist.app_list', compact('search','apps','action','searchStatus','searchBy'));
                }
                else
                {
                    $searchBy = $request->searchBy ?? '';
                    $searchStatus = $request->searchStatus ?? '';
                    $apps = App::sortable(['app_id','desc'])->where('app_deleted','0')->Where("app_name", 'like', '%' . $search . '%')->orWhere("app_description", 'like', '%' . $search . '%')->paginate($action);
                    return view('admin.applist.app_list', compact('search','apps','action','searchStatus','searchBy'));
                }
            }
            else if($action != null)
            {
                $category = App::sortable(['app_id','desc'])->where('app_deleted','0')->paginate($action);
                return view('admin.applist.app_list', ['apps' => $category,'action' => $action]);
            }
            else
            {
                if ($request->ajax()) {
                    $data = App::latest()->get();
                    return Datatables::of($data)
                        ->addColumn('checkbox', function($row) {
                            return '<input type="checkbox" class="checkedAll checkator_source" name="galleryIds[]" value="'.$row->app_id.'" id="checked_'.$row->app_id.'" >' .
                                   '<label for="checked_'.$row->app_id.'" class="toggle"></label>';
                        })
                        ->addColumn('full_details', function($row) {
                            return '<a class="text-primary" title="Full Details View ('.$row->app_id.')">#'.$row->app_id.'</a>';
                        })
                        ->addColumn('edit', function($row) {
                            return '<a href="'.url("admin/view_app/".$row->app_id).'">' .
                                   '<button type="button" data-toggle="tooltip" title="App Info" class="btn btn-link btn-info" data-original-title="App Info">' .
                                   '<i class="fa fa-info"></i>' .
                                   '</button>' .
                                   '</a>' .
                                   '<form action="'.url("admin/edit_app/".$row->app_id).'" method="POST" style="padding-left:5px;">' .
                                   csrf_field() .
                                   '<button type="submit" data-toggle="tooltip" title="Edit" class="btn btn-link btn-primary " data-original-title="Edit">' .
                                   '<i class="fa fa-edit"></i>' .
                                   '</button>' .
                                   '</form>';
                        })
                        ->addColumn('map', function($row) {
                            return ($row->app_mapping_permission == 0) ? '-' : '<a target="_blank" href="'.url("admin/mapped_app/".$row->app_id).'">' .
                                   '<button type="button" data-toggle="tooltip" title="Map" class="btn btn-link btn-success" data-original-title="Map">' .
                                   '<i class="fas fa-folder fa-lg"></i>' .
                                   '</button>' .
                                   '</a>';
                        })
                        ->addColumn('edit_subscription', function($row) {
                            return '<a href="" target="_blank">' .
                                   '<button type="button" data-toggle="tooltip" title="Edit Subscription" class="btn btn-link btn-secondary " data-original-title="Edit Subscription">' .
                                   '<i class="fa fa-edit"></i>' .
                                   '</button>' .
                                   '</a>';
                        })
                        ->addColumn('edit_ads_info', function($row) {
                            return '<a href="" target="_blank">' .
                                   '<button type="button" data-toggle="tooltip" title="Edit Ads Info" class="btn btn-link btn-secondary " data-original-title="Edit Ads Info">' .
                                   '<i class="fa fa-edit"></i>' .
                                   '</button>' .
                                   '</a>';
                        })
                        ->addColumn('status', function($row) {
                            return ($row->app_deleted == '0') ? '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Active</span>' :
                                                                 '<span class="badge bg-danger rounded-fill" ><i class="bi bi-exclamation-octagon me-1"></i> Deactivate</span>';
                        })
                        ->addColumn('action', function($row) {
                            return '<form action="'.url('admin/app_status_update').'" method="POST">' .
                                   csrf_field() .
                                   '<input type="hidden" name="app_id" value="'.$row->app_id.'">' .
                                   (($row->app_deleted == '0') ? '<input type="hidden" name="status" value="0">' .
                                                                 '<button type="submit"  title="Enable" id="enable" name="enable" title="" class="btn_status btn btn-success btn-sm enable">' .
                                                                 '<i class="bi bi-check-circle me-1"></i>' .
                                                                 '</button>' :
                                                                 '<input type="hidden" name="status" value="1">' .
                                                                 '<button type="submit" title="Disable" id="disable" title="" name="disable" class="btn_status btn btn-success btn-sm" style="background: #ffad46!important;border-color: #ffad46!important;color: #fff!important;">' .
                                                                 '<i class="bi bi-exclamation-octagon me-1"></i>' .
                                                                 '</button>') .
                                   '</form>';
                        })
                        ->rawColumns(['checkbox', 'full_details', 'edit', 'map', 'edit_subscription', 'edit_ads_info', 'status', 'action'])
                        ->make(true);
                }
                $category = App::sortable(['app_id','desc'])->where('app_deleted','0')->paginate(10);
                return view('admin.applist.app_list', ['apps' => $category]);
            }
        }
        else
        {
            return redirect()->route('admin/dashboard')->with("error","Access Denied");
        }
    }

    function Add_list(Request $request){
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
            $getKeyIdDetails = iosKeyId::all();
            $getTeamIdDetails = iosTeamId::all();
            $template_Arr = AppsAudioManager::all();
            $AppTextToSpeechManager_Android = AppTextToSpeechManager::where('dev_type_android','Android')->get();
            $AppTextToSpeechManager_Ios = AppTextToSpeechManager::where('dev_type_ios','IOS')->get();
            $get_image_app_id = ImageAppList::all();
            $getglobalappversionid = Version::all();
            $get_commentry_appid = AppList::all();
            $get_quotes_app_id = QuotesAppList::all();
            $get_video_app_id = VideoAppList::all();
            $get_language = Language::all();
            $app_id = 4;
            $category_ids = DB::connection('mysql_second')->table('image_mapped_app_categories')
                ->select('category_id')
                ->where('app_id', $app_id)
                ->get()
                ->pluck('category_id');

            $get_verse_image_app_id = DB::connection('mysql_second')->table('image_category_lists')
                ->select('id', 'category_name')
                ->whereIn('id', $category_ids)
                ->get();


            $compact = compact(
                'getTeamIdDetails',
                'getKeyIdDetails',
                'template_Arr',
                'AppTextToSpeechManager_Android',
                'AppTextToSpeechManager_Ios',
                'getglobalappversionid',
                'get_commentry_appid',
                'get_image_app_id',
                'get_quotes_app_id',
                'get_video_app_id',
                'get_verse_image_app_id',
                'get_language'
            );

            return view('admin.applist.add_app',$compact);
        }
        else
        {
            return redirect()->route('admin/dashboard')->with("error","Access Denied");
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'app_name' => 'required',
            'app_type_version' => 'required',
            'is_pusn_notify_app' => 'required',
            'device_type_a' => 'required_without:device_type_i',
            'device_type_i' => 'required_without:device_type_a',
            'android_api_key' => 'required_if:device_type_a,1',
            'android_package_name' => 'required_if:device_type_a,1',
            'ios_key_id' => 'required_if:device_type_i,1',
            'ios_team_id' => 'required_if:device_type_i,1',
            'ios_bundle_id' => 'required_if:device_type_i,1',
            'add_audio' => 'required',
            'audio_app_id' => 'required_if:add_audio,1',
            'is_voice_speech_audio_available' => 'required',
            'text_to_speech_id_ios' => 'required_if:device_type_i,1,is_voice_speech_audio_available,1',
            'text_to_speech_id_android' => 'required_if:device_type_a,1,is_voice_speech_audio_available,1',
            'calendar_app_id' => 'required',
            'quiz_cat_id' => 'required',
            'wallpaper_cat_id' => 'required',
            'verse_editor_app_id' => 'required',
        ]);

        if($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }

        $app_name = $request->input('app_name');
        $device_type_a = $request->input('device_type_a', '');
        $device_type_i = $request->input('device_type_i', '');
        $app_type_version = $request->input('app_type_version');

        if ($device_type_a === '1') {
            $device_type_a = 'Android';
            $android_package_name = $request->input('android_package_name');
            $android_api_key = $request->input('android_api_key');
            $android_sender_id = $request->input('android_sender_id');
        } else {
            $device_type_a = '';
            $android_package_name = null;
            $android_api_key = null;
            $android_sender_id = null;
        }

        if ($device_type_i === '1') {
            $device_type_i = 'IOS';
            $ios_apple_id = $request->input('ios_apple_id');
            $ios_bundle_id = $request->input('ios_bundle_id');
            $ios_key_id = $request->input('ios_key_id');
            $ios_team_id = $request->input('ios_team_id');
        } else {
            $device_type_i = '';
            $ios_apple_id = '';
            $ios_bundle_id = '';
            $ios_key_id = 0;
            $ios_team_id = 0;
        }

        $add_audio = $request->input('add_audio');
        $audio_app_id = $request->input('audio_app_id', 0);

        $is_voice_speech_audio_available = $request->input('is_voice_speech_audio_available');
        $text_to_speech_id_ios = $request->input('text_to_speech_id_ios', 0);
        $text_to_speech_id_android = $request->input('text_to_speech_id_android', 0);

        $calendar_app_id = $request->input('calendar_app_id');
        $quiz_cat_id = $request->input('quiz_cat_id');
        $wallpaper_cat_id = $request->input('wallpaper_cat_id');
        $verse_editor_app_id = $request->input('verse_editor_app_id');

        // Insert into database
        $inserted = DB::table('app_details')->insert([
            'app_name' => $app_name,
            'device_type_a' => $device_type_a,
            'device_type_i' => $device_type_i,
            'app_type_version' => $app_type_version,
            'android_package_name' => $android_package_name,
            'android_api_key' => $android_api_key,
            'android_sender_id' => $android_sender_id,
            'ios_apple_id' => $ios_apple_id,
            'ios_bundle_id' => $ios_bundle_id,
            'ios_key_id' => $ios_key_id,
            'ios_team_id' => $ios_team_id,
            'add_audio' => $add_audio,
            'audio_app_id' => $audio_app_id,
            'is_voice_speech_audio_available' => $is_voice_speech_audio_available,
            'text_to_speech_id_ios' => $text_to_speech_id_ios,
            'text_to_speech_id_android' => $text_to_speech_id_android,
            'calendar_app_id' => $calendar_app_id,
            'quiz_cat_id' => $quiz_cat_id,
            'wallpaper_cat_id' => $wallpaper_cat_id,
            'verse_editor_app_id' => $verse_editor_app_id,
        ]);

        if (!$inserted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to add app details to the database.',
            ], 500);
        }

        // Handle file upload for bibleImage field
        if ($request->hasFile('bibleImage')) {
            $bibleImage = $request->file('bibleImage');

            if ($bibleImage->isValid()) {
                $extension = $bibleImage->getClientOriginalExtension();
                $allowedExtensions = ['jpg', 'jpeg', 'png'];

                if (!in_array($extension, $allowedExtensions)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid file format for bibleImage. Only JPG, JPEG, and PNG are allowed.',
                    ], 400);
                }

                $maxSize = 5 * 1024; // 5MB
                if ($bibleImage->getSize() > $maxSize) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'File size exceeds the maximum allowed limit of 5MB.',
                    ], 400);
                }

                $destinationPath = 'public/images';
                $bibleImage->storeAs($destinationPath, 'bibleImage.' . $extension);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to upload bibleImage. Please try again.',
                ], 500);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'App details added successfully.',
        ]);
    }

    public function edit($id){
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
            $getAppsDetails = App::whereAppId($id)->first();
            $results = App::select('app_id', 'app_name', 'app_type_android', 'app_type_ios', 'app_type_version')
                            ->where('app_id', '!=', $id)
                            ->where('app_mapping_permission', '1')
                            ->where('app_deleted', '0')
                            ->get();
            return view('admin.applist.mapped_app_category', compact('getAppsDetails','results'));
        }
        else
        {
            return redirect()->route('admin.dashboard')->with("error","Access Denied");
        }

    }

    public function view($id){
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
            $getAppsDetails = App::whereAppId($id)->first();

            $getKeyIdDetails = iosKeyId::select(['key_id','p8_file'])->where('id',$getAppsDetails->ios_key_id)->get()->toArray();

            $getTeamIdDetails = iosTeamId::select('team_id')->where('id',$getAppsDetails->ios_team_id)->first();

            $app = DB::table('apps')->select('app_table_name')->where('app_id', $getAppsDetails->app_id)->first();
            $app_table_name = $app->app_table_name;
            $total_a_count = 0;
            $total_i_count = 0;

            if ($app_table_name != "") {
                $deviceCounts = DB::table($app_table_name)
                    ->select(DB::raw('SUM(CASE WHEN dev_type = "1" THEN 1 ELSE 0 END) AS android_count'),
                            DB::raw('SUM(CASE WHEN dev_type = "2" THEN 1 ELSE 0 END) AS ios_count'))
                    ->first();

                $total_a_count = !empty($deviceCounts->android_count) ? $deviceCounts->android_count : 0;
                $total_i_count = !empty($deviceCounts->ios_count) ? $deviceCounts->ios_count : 0;
            }

            $totalCount = $total_a_count + $total_i_count;
            $output = "Total Count : $totalCount <br>";
            if (!empty($getAppsDetails->app_type_ios)) {
                $output .= "IOS Devices Count : $total_i_count <br>";
            }

            if (!empty($getAppsDetails->app_type_android)) {
                $output .= "Android Devices Count : $total_a_count";
            }
            $app_id = $getAppsDetails->app_id;
            $app = DB::table('apps')->select('app_table_name')->where('app_id', $app_id)->first();
            $app_table_name = $app->app_table_name;

            $total_a_count = 0;
            $total_i_count = 0;

            if ($app_table_name != "") {
                $cur_Date = date('Y-m-d');

                $deviceCounts = DB::table($app_table_name)
                    ->select(DB::raw('SUM(CASE WHEN dev_type = "1" THEN 1 ELSE 0 END) AS android_count'),
                            DB::raw('SUM(CASE WHEN dev_type = "2" THEN 1 ELSE 0 END) AS ios_count'))
                    ->where('created_at', 'LIKE', "%$cur_Date%")
                    ->first();

                $total_a_count = !empty($deviceCounts->android_count) ? $deviceCounts->android_count : 0;
                $total_i_count = !empty($deviceCounts->ios_count) ? $deviceCounts->ios_count : 0;
            }

            $totalCount = $total_a_count + $total_i_count;
            $output_1 = "Total Count : $totalCount <br>";

            if (!empty($getAppsDetails->app_type_ios)) {
                $output_1 .= "iOS Devices Count : $total_i_count <br>";
            }

            if (!empty($getAppsDetails->app_type_android)) {
                $output_1 .= "Android Devices Count : $total_a_count";
            }

            $result = [
                "app_Audio_Basepath" => "",
                "app_Audio_Basepath_Type" => "",
            ];

            $queryResult = DB::table('apps_audio_manager')
                ->select('audio_app_base_path', 'audio_app_base_path_type')
                ->where('audio_app_id', $getAppsDetails->audio_app_id)
                ->where('is_deleted', 0)
                ->first();

            if ($queryResult) {
                $result = [
                    "app_Audio_Basepath" => $queryResult->audio_app_base_path ?: "",
                    "app_Audio_Basepath_Type" => $queryResult->audio_app_base_path_type ?: "",
                ];
            }
            return view('admin.applist.view_app_list', compact('getAppsDetails','getKeyIdDetails','getTeamIdDetails','output','output_1','result'));
        }
        else
        {
            return redirect()->route('admin.dashboard')->with("error","Access Denied");
        }

    }

    public function active(Request $request){
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

    public function delete(Request $request, $id){
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

    public function EditApp($id){
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
            $edit_app_id = App::find($id);
            $getKeyIdDetails = iosKeyId::all();
            $getTeamIdDetails = iosTeamId::all();
            $template_Arr = AppsAudioManager::all();
            $AppTextToSpeechManager_Android = AppTextToSpeechManager::where('dev_type_android','Android')->get();
            $AppTextToSpeechManager_Ios = AppTextToSpeechManager::where('dev_type_ios','IOS')->get();
            $get_image_app_id = ImageAppList::all();
            $getglobalappversionid = Version::all();
            $get_commentry_appid = AppList::all();
            $get_quotes_app_id = QuotesAppList::all();
            $get_video_app_id = VideoAppList::all();
            $get_language = Language::all();
            $app_id = 4;
            $category_ids = DB::connection('mysql_second')->table('image_mapped_app_categories')
                ->select('category_id')
                ->where('app_id', $app_id)
                ->get()
                ->pluck('category_id');

            $get_verse_image_app_id = DB::connection('mysql_second')->table('image_category_lists')
                ->select('id', 'category_name')
                ->whereIn('id', $category_ids)
                ->get();


            $compact = compact(
                'edit_app_id',
                'getTeamIdDetails',
                'getKeyIdDetails',
                'template_Arr',
                'AppTextToSpeechManager_Android',
                'AppTextToSpeechManager_Ios',
                'getglobalappversionid',
                'get_commentry_appid',
                'get_image_app_id',
                'get_quotes_app_id',
                'get_video_app_id',
                'get_verse_image_app_id',
                'get_language'
            );
            return view('admin.applist.edit_app_list', $compact);
        }
        else
        {
            return redirect()->route('admin.dashboard')->with("error","Access Denied");
        }
    }

    public function EditAppList(Request $request, $id){
        $app_id = $id;
        $request->validate([
            'app_name' => 'required',
            'app_type_version' => 'required',
            'is_pusn_notify_app' => 'required',
            'device_type_a' => 'required_without_all:device_type_i',
            'device_type_i' => 'required_without_all:device_type_a',
            'android_api_key' => 'required_if:device_type_a,',
            'android_package_name' => 'required_if:device_type_a,',
            'ios_key_id' => 'required_if:device_type_i,',
            'ios_team_id' => 'required_if:device_type_i,',
            'ios_bundle_id' => 'required_if:device_type_i,',
            'add_audio' => 'required',
            'audio_app_id' => 'required_if:add_audio,1',
            'is_voice_speech_audio_available' => 'required',
            'text_to_speech_id_ios' => 'required_if:is_voice_speech_audio_available,1',
            'text_to_speech_id_android' => 'required_if:is_voice_speech_audio_available,1',
            'calendar_app_id' => 'required',
            'quiz_cat_id' => 'required',
            'wallpaper_cat_id' => 'required',
            'verse_editor_app_id' => 'required',
        ]);

        $app_name = $request->input('app_name');
        $app_name = $app_name ? trim($app_name) : '';

        if ($request->has('device_type_a')) {
            $device_type_a = $request->input('device_type_a');
            $device_type_a = $device_type_a ? trim($device_type_a) : '';
        }

        if ($request->has('device_type_i') && !empty($request->input('device_type_i'))) {
            $device_type_i = $request->input('device_type_i');
            $device_type_i = $device_type_i ? trim($device_type_i) : '';
        }

        $app_type_version = $request->input('app_type_version');
        $app_type_version = $app_type_version ? trim($app_type_version) : '';

        if ($request->has('device_type_a') && $request->input('device_type_a') == 1) {
            $device_type_a = 'Android';
            $android_package_name = $request->input('android_package_name');
            $android_package_name = $android_package_name ? trim($android_package_name) : "";
            $android_api_key = $request->input('android_api_key');
            $android_api_key = $android_api_key ? trim($android_api_key) : "";
            $android_sender_id = $request->input('android_sender_id');
            $android_sender_id = $android_sender_id ? trim($android_sender_id) : "";
        } else {
            $device_type_a = " ";
            $android_package_name = " ";
            $android_api_key = "";
            $android_sender_id = "";
        }

        if ($request->has('device_type_i') && $request->input('device_type_i') == 1) {
            $device_type_i = 'IOS';
            $ios_apple_id = $request->input('ios_apple_id');
            $ios_apple_id = $ios_apple_id ? trim($ios_apple_id) : '';
            $ios_bundle_id = $request->input('ios_bundle_id');
            $ios_bundle_id = $ios_bundle_id ? trim($ios_bundle_id) : '';
            $ios_key_id = $request->input('ios_key_id');
            $ios_key_id = $ios_key_id ? trim($ios_key_id) : '';
            $ios_team_id = $request->input('ios_team_id');
            $ios_team_id = $ios_team_id ? trim($ios_team_id) : '';
        } else {
            $device_type_i = '';
            $ios_apple_id = '';
            $ios_bundle_id = '';
            $ios_key_id = 0;
            $ios_team_id = 0;
        }

        $add_audio = $request->input('add_audio');
        $add_audio = $add_audio ? trim($add_audio) : '';

        if ($add_audio == 1 || $add_audio == "1") {
            $audio_app_id = $request->input('audio_app_id');
            $audio_app_id = $audio_app_id ? trim($audio_app_id) : '';
        } else {
            $add_audio = 0;
            $audio_app_id = 0;
        }

        $is_voice_speech_audio_available = $request->input('is_voice_speech_audio_available');
        $is_voice_speech_audio_available = $is_voice_speech_audio_available ? trim($is_voice_speech_audio_available) : '';

        if ($is_voice_speech_audio_available == 1 || $is_voice_speech_audio_available == "1") {
            if (!empty($request->input('text_to_speech_id_ios'))) {
                $text_to_speech_id_ios = $request->input('text_to_speech_id_ios');
                $text_to_speech_id_ios = $text_to_speech_id_ios ? trim($text_to_speech_id_ios) : '';
                $is_text_to_speech_available_ios = 1;
            } else {
                $text_to_speech_id_ios = 0;
                $is_text_to_speech_available_ios = 0;
            }

            if (!empty($request->input('text_to_speech_id_android'))) {
                $text_to_speech_id_android = $request->input('text_to_speech_id_android');
                $text_to_speech_id_android = $text_to_speech_id_android ? trim($text_to_speech_id_android) : '';
                $is_text_to_speech_available_android = 1;
            } else {
                $text_to_speech_id_android = 0;
                $is_text_to_speech_available_android = 0;
            }
        } else {
            $is_voice_speech_audio_available = 0;
            $is_text_to_speech_available_ios = 0;
            $text_to_speech_id_ios = 0;
            $is_text_to_speech_available_android = 0;
            $text_to_speech_id_android = 0;
        }

        $is_pusn_notify_app = $request->input('is_pusn_notify_app');

        if ($is_pusn_notify_app == 1 || $is_pusn_notify_app == "1") {
            $is_pusn_notify_app = 'Enable';
        } else {
            $is_pusn_notify_app = 'Disable';
        }

        $calendar_app_id = $request->input('calendar_app_id');
        $calendar_app_id = $calendar_app_id ? trim($calendar_app_id) : '';

        $global_app_version_id = $request->input('global_app_version_id');
        $global_app_version_id = $global_app_version_id ? trim($global_app_version_id) : '';

        $image_app_id = $request->input('image_app_id');
        $image_app_id = $image_app_id ? trim($image_app_id) : '';

        $commentry_appid = $request->input('commentry_appid');
        $commentry_appid = $commentry_appid ? trim($commentry_appid) : '';

        $image_app_id = $request->input('image_app_id');
        $image_app_id = $image_app_id ? trim($image_app_id) : '';

        $quote_app_id = $request->input('quote_app_id');
        $quote_app_id = $quote_app_id ? trim($quote_app_id) : '';

        $video_app_id = $request->input('video_app_id');
        $video_app_id = $video_app_id ? trim($video_app_id) : '';

        $quiz_cat_id = $request->input('quiz_cat_id');
        $quiz_cat_id = $quiz_cat_id ? trim($quiz_cat_id) : '';

        $wallpaper_cat_id = $request->input('wallpaper_cat_id');
        $wallpaper_cat_id = $wallpaper_cat_id ? trim($wallpaper_cat_id) : '';

        $verse_editor_app_id = $request->input('verse_editor_app_id');
        $verse_editor_app_id = $verse_editor_app_id ? trim($verse_editor_app_id) : '';

        $language_name = $request->input('language_name');
        $language_name = $language_name ? trim($language_name) : '';

        $language_code = $request->input('language_code');
        $language_code = $language_code ? trim($language_code) : '';

        $is_pusn_notify_app = $request->input('is_pusn_notify_app');
        $is_pusn_notify_app = $is_pusn_notify_app ? trim($is_pusn_notify_app) : '';



        if($is_pusn_notify_app==1 || $is_pusn_notify_app =="1")
        {
            $is_pusn_notify_app = 'Enable';
        }
        else
        {
            $is_pusn_notify_app = 'Disable';
        }

        $app_theme_color = $request->input('app_theme_color');
        $app_theme_color = $app_theme_color ? trim($app_theme_color) : '';
        $app_theme_color = isset($app_theme_color) && !empty($app_theme_color) ? $app_theme_color : '';

        $app_status_theme_color = $request->input('app_status_theme_color');
        $app_status_theme_color = $app_status_theme_color ? trim($app_status_theme_color) : '';
        $app_status_theme_color = isset($app_status_theme_color) && !empty($app_status_theme_color) ? $app_status_theme_color : '';

        if (!empty($app_theme_color)) {
            if (substr($app_theme_color, 0, 1) != '#') {
                $app_theme_color = '#' . $app_theme_color;
            }
        }

        if (!empty($app_status_theme_color)) {
            if (substr($app_status_theme_color, 0, 1) != '#') {
                $app_status_theme_color = '#' . $app_status_theme_color;
            }
        }

        $country_name = $request->input('country_name');
        $country_name = $country_name ? trim($country_name) : '';

        $country_code = $request->input('country_code');
        $country_code = $country_code ? trim($country_code) : '';

        $isPasswordProtected = $request->input('isPasswordProtected');
        if ($isPasswordProtected == '1') {
            $isPasswordProtected = "1";
        } else {
            $isPasswordProtected = "0";
        }
        $app_mapping_permission = trim(request('app_mapping_permission'));

        if ($app_mapping_permission == '1') {
            $app_mapping_permission = "1";
        } else {
            $app_mapping_permission = "0";
        }

        $bibleVersion = $request->input('bibleVersion');
        $bibleVersion = $bibleVersion ? trim($bibleVersion) : '';

        $bibleContentURL = $request->input('bibleContentURL');
        $bibleContentURL = $bibleContentURL ? trim($bibleContentURL) : '';

        $is_commentry_enabled  = $request->input('is_commentry_enabled') ? trim($request->input('is_commentry_enabled')) : 0;
        $android_bible_active_status = $request->input('android_bible_active_status');
        if ($android_bible_active_status == '1') {
            $android_bible_active_status = "1";
        } else {
            $android_bible_active_status = "0";
        }

        $ios_bible_active_status = $request->input('ios_bible_active_status');
        if ($ios_bible_active_status == '1') {
            $ios_bible_active_status = "1";
        } else {
            $ios_bible_active_status = "0";
        }
        $copyrights = trim(request('copyrights'));
        $bibleImageUploadType = trim(request('bibleImageUploadType'));
        $copyright_name = trim(request('copyright_name', ''));
        $copyright_url = trim(request('copyright_url', ''));

        if ($request->hasFile('bibleImage') && $request->file('bibleImage')->isValid()) {
            $allowedExtensions = ["png", "jpg", "jpeg"];
            $maxsize = 15 * 1024 * 1024; // 15MB

            $file = $request->file('bibleImage');
            $fileSize = $file->getSize();
            $extension = $file->getClientOriginalExtension();

            if (!in_array($extension, $allowedExtensions)) {
                $setMsg = [
                    "alert_type" => "alert-danger",
                    "message" => "File Format Invalid!! Only allowed extensions are: " . implode(', ', $allowedExtensions)
                ];
                return $setMsg;
            }

            if ($fileSize > $maxsize) {
                $setMsg = [
                    "alert_type" => "alert-danger",
                    "message" => "File Size too large! Maximum allowed size: 15MB"
                ];
                return $setMsg;
            }

            $changeFileName = "bible_img" . "_" . time() . '.' . $extension;
            $uploadDir = 'uploads/bible_img/';
            $filePathForDb = $uploadDir . $changeFileName;

            if (!$file->storeAs($uploadDir, $changeFileName)) {
                $setMsg = [
                    "alert_type" => "alert-danger",
                    "message" => "File Uploading Error.."
                ];
                return $setMsg;
            }

            $bibleImage = $filePathForDb;
        } else {
            $bibleImage = '';
        }

        $is_left_to_right = trim($request->input('is_left_to_right'));
        $is_reading_plan_enabled = trim($request->input('is_reading_plan_enabled'));

        $bibleShortDescription = trim($request->input('bibleShortDescription'));
        $bibleShortDescription = !empty($bibleShortDescription) ? $bibleShortDescription : "";
        if (true) {
            if ($device_type_i === 'IOS') {
                if (!$this->CheckAlreadyExists_except('apps', 'app_id', 'ios_bundle_id', $ios_bundle_id, 'app_id', $app_id)) {
                    return [
                        "alert_type" => "alert-danger",
                        "message" => "IOS Bundle ID already exists!!"
                    ];
                }
            }

            if ($device_type_a === 'Android') {
                if (!$this->CheckAlreadyExists_except('apps', 'app_id', 'android_package_name', $android_package_name, 'app_id', $app_id)) {
                    return [
                        "alert_type" => "alert-danger",
                        "message" => "Android Package name already exists!!"
                    ];
                }
            }
            $cur_time = now();

            $data = [
                'app_name' => $app_name ?? "",
                'app_type_version' => $app_type_version ?? "",
                'push_notification_app' => $is_pusn_notify_app ?? "",
                'app_type_android' => $device_type_a ?? "",
                'app_type_ios' => $device_type_i ?? "",
                'ios_bundle_id' => $ios_bundle_id ?? "",
                'ios_apple_id' => $ios_apple_id ?? "",
                'ios_key_id' => $ios_key_id ?? "",
                'ios_team_id' => $ios_team_id ?? "",
                'android_package_name' => $android_package_name ?? "",
                'android_api_key' => $android_api_key ?? "",
                'android_sender_id' => $android_sender_id ?? "",
                'add_audio' => $add_audio ?? "",
                'audio_app_id' => $audio_app_id ?? "",
                'calendar_app_id' => $calendar_app_id ?? "",
                'global_app_version_id' => $global_app_version_id ?: 0,
                'is_commentry_enabled' => $is_commentry_enabled ?? 0,
                'commentry_appid' => $commentry_appid ?: 0,
                'image_app_id' => $image_app_id ?: 0,
                'quote_app_id' => $quote_app_id ?: 0,
                'video_app_id' => $video_app_id ?: 0,
                'quiz_cat_id' => $quiz_cat_id ?? "",
                'wallpaper_cat_id' => $wallpaper_cat_id ?? "",
                'verse_editor_app_id' => $verse_editor_app_id ?? "",
                'language_name' => $language_name ?? "",
                'language_code' => $language_code ?? "",
                'app_mapping_permission' => $app_mapping_permission ?? "",
                'ios_bible_active_status' => $ios_bible_active_status ?? "",
                'android_bible_active_status' => $android_bible_active_status ?? "",
                'bibleContentURL' => $bibleContentURL ?? "",
                'isPasswordProtected' => $isPasswordProtected ?? "",
                'copyrights' => $copyrights ?? "",
                'bibleVersion' => $bibleVersion ?? "",
                'country_name' => $country_name ?? "",
                'country_code' => $country_code ?? "",
                'bibleImage' => $bibleImage ?? "",
                'bibleShortDescription' => $bibleShortDescription ?? "",
                'bibleImageUploadType' => $bibleImageUploadType ?? "",
                'app_theme_color' => $app_theme_color ?? "",
                'app_status_theme_color' => $app_status_theme_color ?? "",
                'copyright_name' => $copyright_name ?? "",
                'copyright_url' => $copyright_url ?? "",
                'is_left_to_right' => $is_left_to_right ?? "",
                'is_voice_speech_audio_available' => $is_voice_speech_audio_available ?? "",
                'is_reading_plan_enabled' => $is_reading_plan_enabled ?? "",
                'is_text_to_speech_available_ios' => $is_text_to_speech_available_ios ?? "",
                'text_to_speech_id_ios' => $text_to_speech_id_ios ?? "",
                'is_text_to_speech_available_android' => $is_text_to_speech_available_android ?? "",
                'text_to_speech_id_android' => $text_to_speech_id_android ?? "",
                'updated_at' => $cur_time
            ];

            $app = App::where('app_id', $app_id)->first();
            if ($app) {
                $app->update($data);
                return back()->with('success_message','App Details Updated Successfully!!');
            }else {
                return back()->with('success_message','App Details Updating Failed..');
            }
        } else {
            return back()->with('failer_message','App Name already exists!!');
        }

    }

    private function CheckAlreadyExists_except($table_name, $fetching_field_name, $field_name, $value, $column_name, $column_value){
        $query = DB::table($table_name)
            ->select($fetching_field_name)
            ->where($field_name, $value)
            ->where($column_name, '!=', $column_value)
            ->where('app_deleted', 0)
            ->get();
        if ($query->isEmpty()) {
            return true;
        } else {
            return false;
        }
    }

    public function ajax_server(Request $request){
        $bcUplKey = 'hasgdt8&^*&T@G4bjehds987';

        if ($request->input('bc_upl_key') == $bcUplKey) {
            $appID = $request->input('app_id_bcf', '');

            if (!empty($appID)) {
                if ($request->hasFile('bc_zip_file') && $request->file('bc_zip_file')->isValid()) {
                    $allowedExtensions = ['zip'];
                    $maxFileSize = 50; // Read the max file size from your configuration

                    $file = $request->file('bc_zip_file');
                    $extension = $file->getClientOriginalExtension();

                    if (!in_array($extension, $allowedExtensions)) {
                        return response()->json([
                            'res' => false,
                            'msg' => 'File Format Invalid!! Only allowed extension is: ' . implode(', ', $allowedExtensions)
                        ]);
                    }
                    $fileSize = ($file->getSize() / 1024 / 1024);
                    if($fileSize > $maxFileSize) {
                        return response()->json([
                            'res' => false,
                            'msg' => 'File Size too large! Maximum allowed size: ' .$maxFileSize. ' MB'
                        ]);
                    }

                    $mainFolder = 'bible_content';
                    $timeMilli = (int) round(microtime(true) * 1000);
                    $changeFileName = $appID . '_' . $timeMilli . '.' . $extension;

                    $uploadDir = public_path('uploads/' . $mainFolder . '/');
                    $filePathForDb = asset('uploads/'.$mainFolder . '/' . $changeFileName); // Replace with your actual upload path

                    $filePath = $uploadDir . $changeFileName;
                    if (!file_exists($filePath)) {
                        if ($file->move($uploadDir, $changeFileName)) {
                            $res = [
                                'res' => true,
                                'msg' => 'File uploaded successfully!',
                                'data' => $filePathForDb
                            ];
                        } else {
                            $res = [
                                'res' => false,
                                'msg' => 'File Moving Error!'
                            ];
                        }
                    } else {
                        $res = [
                            'res' => false,
                            'msg' => 'File Name Already exists!'
                        ];
                    }

                    return response()->json($res);
                } else {
                    return response()->json([
                        'res' => false,
                        'msg' => 'File Error! Check the file.'
                    ]);
                }
            } else {
                return response()->json([
                    'res' => false,
                    'msg' => 'Invalid App ID!'
                ]);
            }
        } else {
            return response()->json([
                'res' => false,
                'msg' => 'Invalid Request!'
            ]);
        }
    }

    public function add_import(Request $request){
        return view('admin.applist.add_import');
    }

    public function bluk_insert(Request $request){
        $request->validate([
             'file'=>'required'
        ]);
        $file = $request->file('file');
        Excel::import(new Appimport,$file); // Replace YourImport with the appropriate import class for your model
        return redirect()->back();
    }

}
