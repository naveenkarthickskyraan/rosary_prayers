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
use App\Models\NotificationData;
use App\Models\NotificationFilterData;
use App\Models\NotificationSendingData;
use App\Models\NotificationSendData;
use App\Models\QuotesAppList;
use App\Models\Language;
use App\Models\PushNotifyApp;
use Illuminate\Support\Facades\Storage;
use App\Imports\AppImport;
use App\DataTables\AppDataTable;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use DataTables;
use Artisan;


use Google\Auth\Credentials\ServiceAccountCredentials;

class PushNotifyAppController extends Controller
{
    public function show(Request $request)
    {
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
                    $apps = App::sortable(['app_id','desc'])->where('push_notification_app','Enable')->where('app_deleted','0')->where('is_active',$request->searchStatus)->Where("app_name", 'like', '%' . $search . '%')->paginate($action);
                    return view('admin.pushnotify.app_list', compact('search','apps','action','searchStatus','searchBy'));
                }
                else
                {
                    $searchBy = $request->searchBy ?? '';
                    $searchStatus = $request->searchStatus ?? '';
                    $apps = App::sortable(['app_id','desc'])->where('push_notification_app','Enable')->where('app_deleted','0')->Where("app_name", 'like', '%' . $search . '%')->orWhere("app_description", 'like', '%' . $search . '%')->paginate($action);
                    foreach ($apps as $app) {
                        $dynamicTableName = $app->app_table_name;

                        // Get the count of the results for the dynamic table
                        $count = DB::table($dynamicTableName)->count();
                        $app->record_count = $count;
                    }
                    return view('admin.pushnotify.app_list', compact('search','apps','action','searchStatus','searchBy'));
                }
            }
            else if($action != null)
            {
                $category = App::sortable(['app_id','desc'])->where('push_notification_app','Enable')->where('app_deleted','0')->paginate($action);
                foreach ($category as $app) {
                    $dynamicTableName = $app->app_table_name;

                    // Get the count of the results for the dynamic table
                    $count = DB::table($dynamicTableName)->count();
                    $app->record_count = $count;
                }
                return view('admin.pushnotify.app_list', ['apps' => $category,'action' => $action]);
            }
            else
            {
                $category = App::sortable(['app_id','desc'])->where('push_notification_app','Enable')->where('app_deleted','0')->paginate(10);
                foreach ($category as $app) {
                    $dynamicTableName = $app->app_table_name;

                    // Get the count of the results for the dynamic table
                    $count = DB::table($dynamicTableName)->count();
                    $app->record_count = $count;
                }
                return view('admin.pushnotify.app_list', ['apps' => $category]);
            }
        }
        else
        {
            return redirect()->route('admin/dashboard')->with("error","Access Denied");
        }
    }

    function Add_list()
    {
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
            $getAppDetails = App::where('app_deleted', '=', '0')
                        ->where('push_notification_app', '=', 'Enable')
                        ->orderBy('app_name', 'ASC')
                        ->get();

            $getTemplatepDetails = DB::table('notification_template')
                        ->where('is_deleted', 0)
                        ->orderBy('created_at', 'desc')
                        ->get();
            // dd($getTemplatepDetails);

            $get_notification_typeArr= array(
                '1' => "Default",
                '2' => "Bible Content Navigation",
                '3' => "Celebrations",
                '4' => "Promotions",
            );

            $compact = compact(
                'getAppDetails',
                'getTemplatepDetails',
                'get_notification_typeArr'
            );

            return view('admin.pushnotify.add_app',$compact);
        }
        else
        {
            return redirect()->route('admin/dashboard')->with("error","Access Denied");
        }
    }

//     public function store(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'n_title' => 'required',
//             'n_text' => 'required',
//             'send_type' => 'required',
//             'app_select' => 'required',
//             's_date' => 'required_if:send_type,2',
//             's_time' => 'required_if:send_type,2',
//             'device_ids_list' => 'required',
//             'app_ids_list' => 'required',
//             'app_mode' => 'required',
//             'notification_type' => 'required',
//             'book_num' => 'required_if:notification_type,2',
//             'chapter_num' => 'required_if:notification_type,2',
//             'deeplink_url' => 'required_if:notification_type,4',
//         ]);

//         if($validator->fails()) {
//             return back()->with('errors', $validator->errors());
//         }
        
//         // Retrieve the data from the request
//         $n_title = $request->input('n_title');
//         $n_text = $request->input('n_text');
//         $n_name = $request->input('n_name');
//         $is_template = $request->input('is_template');
//         $send_type = $request->input('send_type');
//         $s_date = $request->input('s_date');
//         $s_time = $request->input('s_time');
//         $app_mode_p_d = $request->input('app_mode');
//         $app_select = implode(",", $request->input('app_select'));
//         $device_ids_list = $request->input('device_ids_list');
//         $device_ids_count = $request->input('device_ids_count');
//         $device_exe_query = $request->input('device_exe_query');
//         $app_ids_list = $request->input('app_ids_list');
//         $notification_type = $request->input('notification_type');
//         $set_book_num = 0;
//         $set_chapter_num = 0;
//         $set_verse_num = 0;
//         $set_deeplink_url = '';

//         if ($notification_type == "2") {
//             $set_book_num = $request->input('book_num');
//             $set_chapter_num = $request->input('chapter_num');
//             $set_verse_num = $request->input('verse_num');
//         }

//         if ($notification_type == "4") {
//             $set_deeplink_url = $request->input('deeplink_url');
//         }

//         $cur_time = now();

//         $valid_schedule_time = 0;

//         if ($send_type == "2") {
//             if (empty($s_date) && empty($s_time)) {
//                 $setMsg = ["error_message" => "Schedule Date and Time Required"];
//                 return $setMsg;
//             } else {
//                 // Check if send type is schedules then validate date and time
//                 // The given date and time should be a future date and 1 hour future in current date time

//                 $date_time = \Carbon\Carbon::parse($s_date . ' ' . $s_time);

//                 // Add 1 hour to the current time
//                 $curDateTime = \Carbon\Carbon::now()->addHour();

//                 // Check if the input date and time is after the current time
//                 if ($date_time->gt($curDateTime)) {
//                     $valid_schedule_time = 1;
//                     // You may want to return a success message here
//                 } else {
//                     $setMsg = ["error_message" => "Schedule Date and Time Is Invalid. Time should be at least 1 hour from the current time."];
//                     return $setMsg;
//                 }
//             }
//         }
//         // Insert data into the database using Laravel's query builder
//         $insQry = NotificationData::insert([
//             'n_title' => $n_title,
//             'n_text' => $n_text,
//             'n_name' => $n_name,
//             'is_template' => $is_template,
//             'send_type' => $send_type,
//             's_date' => $s_date,
//             's_time' => $s_time,
//             'app_select' => $app_select,
//             'app_mode_p_d' => $app_mode_p_d,
//             'target_dev' => $device_ids_list,
//             'target_app' => $app_ids_list,
//             'device_total_count' => $device_ids_count,
//             'notification_type' => $notification_type,
//             'book_num' => $set_book_num,
//             'chapter_num' => $set_chapter_num,
//             'verse_num' => $set_verse_num,
//             'deeplink_url' => $set_deeplink_url,
//             'n_created_at' => $cur_time,
//         ]);

//         if ($insQry) {

//             $notify_Id = NotificationData::orderBy('n_id','desc')->first();
//             if (isset($request->filter_id)) {

//                 // Update the notification ID in notification_filter_data table
//                 $notification_filter_data = NotificationFilterData::find($request->filter_id);
//                 $notification_filter_data->n_id =  $notify_Id->n_id;

//                 $notification_filter_data->save();

//                 // Delete rows with n_id = 0 in notification_filter_data table
//                 NotificationFilterData::where('n_id', 0)->delete();
//             }

//             $n_imgUploadType = $request->input('n_imgUploadType');
//             $n_image_db = "";
//             $n_image_old = $request->input('n_image_old');
//             $n_image_url = $request->input('n_image_url');
            
//             // Image upload logic
//             if ($n_imgUploadType == "1") {
//                 if (!empty($n_image_old)) {
//                     $n_image_db = $n_image_old;
//                 } else {
//                     $img_upload_suc = $this->imageUpload($request, $notify_Id->n_id);
//                     if ($img_upload_suc["status"] == "1" || $img_upload_suc["status"] == 1) {
//                         $n_image_db = asset($img_upload_suc["file_path"]);
//                     } else {
//                         return back()->with('error_message',$img_upload_suc['message']);
//                     }
//                 }
//             } elseif ($n_imgUploadType == "2") {
//                 $n_image_db = !empty($n_image_url) ? $n_image_url : $n_image_old;
//             }

//             DB::table('notification_data')
//                     ->where('n_id', $notify_Id->n_id)
//                     ->update([
//                         'n_image' => $n_image_db,
//                         'n_updated_at' => $cur_time
//                     ]);

//             // Update notification_data table
//             $updateResult = DB::table('notification_data')
//                                 ->where('n_id', $notify_Id->n_id)
//                                 ->update([
//                                     'n_image' => $n_image_db,
//                                     'n_img_upload_type' => $n_imgUploadType,
//                                     'n_updated_at' => $cur_time
//                                 ]);

//             // Check if it's a template
//             if ($is_template == 1) {
//                 // Insert data into notification_template table
//                 $insertResult = DB::table('notification_template')->insert([
//                     'template_name' => $notify_Id->n_id ."-".$n_title,
//                     'n_title' => $n_title,
//                     'n_text' => $n_text,
//                     'n_name' => $n_name,
//                     'n_image' => $n_image_db,
//                     'n_img_upload_type' => $n_imgUploadType,
//                     'created_at' => now(),
//                     'updated_at' => now(), // Assuming created_at is a timestamp field
//                 ]);
//             }


//             if ($send_type == "1") {
//                 if (!empty($device_ids_list) && !empty($app_ids_list)) {
//                     $n_status_arr = ['status' => '', 'msg' => ''];
//                     $device_ids_list = unserialize($device_ids_list);
//                     $notify_datas = [
//                         'n_id' => $notify_Id->n_id,
//                         'n_text' => $n_text,
//                         'n_title' => $n_title,
//                         'n_image' => $n_image_db,
//                         'notification_type' => strval($notification_type),
//                         'book_num' => strval($set_book_num),
//                         'chapter_num' => strval($set_chapter_num),
//                         'verse_num' => strval($set_verse_num),
//                         'deeplink_url' => strval($set_deeplink_url),
//                         'ed' => '',
//                         'pd' => ''
//                     ];

//                     // Sending process variables
//                     $send_app_array=array();
//                     $sending_process = false;
//                     $total_device_count = 0;
//                     $total_device_count_fail = 0;
//                     $total_device_count_suc = 0;
//                     $temp_count_array = [];
//                     $count_array = [
//                         'total_app_count' => count($send_app_array),
//                         'total_device_count' => '0',
//                         'total_device_count_suc' => '0',
//                         'total_device_count_fail' => '0'
//                     ];

//                     if ($device_ids_count == 0 || $device_ids_count == "") {
//                         $setMsg = ['message' => 'Push Notification Sending Failed!! Device Ids Count will be 0 !!'];
//                         return back()->with($setMsg);
//                         exit();
//                     } else {
//                         if (25 >= $device_ids_count) {
//                             foreach ($device_ids_list as $app_id => $device_ids) {
//                                 if ($app_id != "" && !empty($device_ids)) {
//                                     $device_datas = $this->getDeviceIdsOne($notification_type,$device_ids, $app_id, $app_mode_p_d);
                                    
//                                     $total_device_count += count($device_datas);
//                                     $count_array['total_device_count'] = $total_device_count;

//                                     if (count($device_datas) == 0) {
//                                         $n_status_arr['status'] = 'Not Completed';
//                                         $n_status_arr['msg'] .= 'App Id:'.$app_id.' Device count will be 0';

//                                     } else {
//                                         $count_array = $this->setupPushNotifyOne($notify_datas, array_reverse($device_datas), $count_array, $app_mode_p_d);
//                                     }
//                                 } else {
//                                     $n_status_arr['status'] = 'Not Completed';
//                                     $n_status_arr['msg'] = 'App ids or Device ids empty';
//                                 }
//                             }
//                         } else {
//                             foreach ($device_ids_list as $app_id => $device_ids) {
//                                 if ($app_id != "" && !empty($device_ids)) {
//                                     $app_ids_fill = array_fill(0, count($device_ids), $app_id);
//                                     $this->insert_into_inprogress_all_apps($notify_Id->n_id, $app_ids_fill, $device_ids);
//                                     $sending_process = true;
//                                 } else {
//                                     $n_status_arr['status'] = 'Not Completed';
//                                     $n_status_arr['msg'] = 'App ids or Device ids empty';
//                                 }
//                             }
//                         }
//                     }

//                     if ($sending_process) {
//                         $n_status_arr['status'] = 'In Progress';
//                         $n_status_arr['msg'] = 'Processing Device total count exceeded from 5';
//                     } else {
//                         if ($count_array['total_device_count'] == $count_array['total_device_count_suc']) {
//                             $n_status_arr['status'] = 'Completed ( '.$count_array['total_device_count_suc'].' / ' .$count_array['total_device_count']. ')';
//                             $n_status_arr['msg'] = 'All devices sending success';
//                         } else {
//                             $n_status_arr['status'] = 'Completed ( '.$count_array['total_device_count_suc'].' / ' .$count_array['total_device_count']. ')';
//                             $n_status_arr['msg'] = 'Some devices sending failed';
//                         }
//                     }

//                     $n_status = $n_status_arr['status'];
//                     $n_msg = $n_status_arr['msg'];

//                     $cur_time = now();

//                     // Update notification_data table
//                     // dd($notify_Id);
//                     DB::table('notification_data')
//                         ->where('n_id', $notify_Id->n_id)
//                         ->update([
//                             'n_status' => $n_status,
//                             'n_msg' => $n_msg,
//                             'n_updated_at' => $cur_time
//                         ]);

//                     $setMsg = ['success_message' => 'Push Notification Sending Successfully'];
//                     return back()->with($setMsg);
//                     exit();
//                 } else {
//                     $setMsg = ['error_message' => 'Push Notification Failed!! Device Ids or App Ids Missing!!'];
//                     return back()->with($setMsg);
//                     exit();
//                 }
//             }
//             else if ($send_type == "2") {
//                 if ($valid_schedule_time == 1) {
//                     // Query insert for scheduled record fetch
//                     $cur_time = now(); // Use Laravel's helper function to get current time

//                     DB::table('notification_data_query')->insert([
//                         'n_id' => $notify_Id->n_id,
//                         'query' => $device_exe_query,
//                         'created_at' => $cur_time
//                     ]);

//                     // Update notification_data table
//                     $updateResult = DB::table('notification_data')
//                         ->where('n_id', $notify_Id->n_id)
//                         ->update([
//                             'schdedule_send' => 1,
//                             'n_status' => 'Pending'
//                         ]);

//                     if ($updateResult) {
//                         // $n_status_arr = Artisan::call('Scheduled:Cron');
//                         // $n_status_arr = json_decode($n_status_arr, true);

//                         // if ($n_status_arr == 'error') {
//                         //     return back()->with('success_message', $n_status_arr['msg']);
//                         // }

//                         $setMsg = [
//                             "success_message" => "Push Notification Sending (Scheduled Process Setup) Successfully"
//                         ];
//                         return back()->with($setMsg);
//                     } else {
//                         $setMsg = [
//                             "error_message" => "Push Notification Sending Failed! Schedule Time error"
//                         ];
//                         return back()->with($setMsg);
//                     }
//                 } else {
//                     $setMsg = [
//                         "error_message" => "Push Notification Sending Successfully.. Invalid Schedule Time"
//                     ];
//                     return back()->with($setMsg);
//                 }
//             } else {
//                 $setMsg = [
//                     "error_message" => "Push Notification Sending failed"
//                 ];
//                 return back()->with($setMsg);
//             }

//         }
// 		else
// 		{
//             $setMsg = [
//                 "error_message" => "Push Notification Sending failed"
//             ];
//             return back()->with($setMsg);
// 		}
//     }
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'n_title' => 'required',
        'n_text' => 'required',
        'send_type' => 'required',
        'app_select' => 'required',
        's_date' => 'required_if:send_type,2',
        's_time' => 'required_if:send_type,2',
        'device_ids_list' => 'required',
        'app_ids_list' => 'required',
        'app_mode' => 'required',
        'notification_type' => 'required',
        'book_num' => 'required_if:notification_type,2',
        'chapter_num' => 'required_if:notification_type,2',
        'deeplink_url' => 'required_if:notification_type,4',
    ]);

    if ($validator->fails()) {
        return back()->with('errors', $validator->errors());
    }

    // Retrieve the data from the request
    $n_title = $request->input('n_title');
    $n_text = $request->input('n_text');
    $n_name = $request->input('n_name');
    $is_template = $request->input('is_template');
    $send_type = $request->input('send_type');
    $s_date = $request->input('s_date');
    $s_time = $request->input('s_time');
    $app_mode_p_d = $request->input('app_mode');
    $app_select = implode(",", $request->input('app_select'));
    $device_ids_list = $request->input('device_ids_list');
    $device_ids_count = $request->input('device_ids_count');
    $device_exe_query = $request->input('device_exe_query');
    $app_ids_list = $request->input('app_ids_list');
    $notification_type = $request->input('notification_type');
    $set_book_num = 0;
    $set_chapter_num = 0;
    $set_verse_num = 0;
    $set_deeplink_url = '';

    if ($notification_type == "2") {
        $set_book_num = $request->input('book_num');
        $set_chapter_num = $request->input('chapter_num');
        $set_verse_num = $request->input('verse_num');
    }

    if ($notification_type == "4") {
        $set_deeplink_url = $request->input('deeplink_url');
    }

    $cur_time = now();

    $valid_schedule_time = 0;

    if ($send_type == "2") {
        if (empty($s_date) && empty($s_time)) {
            $setMsg = ["error_message" => "Schedule Date and Time Required"];
            return $setMsg;
        } else {
            // Check if send type is scheduled then validate date and time
            // The given date and time should be a future date and 1 hour future from current date time

            $date_time = \Carbon\Carbon::parse($s_date . ' ' . $s_time);

            // Add 1 hour to the current time
            $curDateTime = \Carbon\Carbon::now()->addHour();

            // Check if the input date and time is after the current time
            if ($date_time->gt($curDateTime)) {
                $valid_schedule_time = 1;
            } else {
                $setMsg = ["error_message" => "Schedule Date and Time Is Invalid. Time should be at least 1 hour from the current time."];
                return $setMsg;
            }
        }
    }

    // Insert data into the database using Laravel's query builder
    $insQry = NotificationData::insert([
        'n_title' => $n_title,
        'n_text' => $n_text,
        'n_name' => $n_name,
        'is_template' => $is_template,
        'send_type' => $send_type,
        's_date' => $s_date,
        's_time' => $s_time,
        'app_select' => $app_select,
        'app_mode_p_d' => $app_mode_p_d,
        'target_dev' => $device_ids_list,
        'target_app' => $app_ids_list,
        'device_total_count' => $device_ids_count,
        'notification_type' => $notification_type,
        'book_num' => $set_book_num,
        'chapter_num' => $set_chapter_num,
        'verse_num' => $set_verse_num,
        'deeplink_url' => $set_deeplink_url,
        'n_created_at' => $cur_time,
    ]);

    if (!$insQry) {
        $setMsg = ["error_message" => "Push Notification Sending failed"];
        return back()->with($setMsg);
    }

    $notify_Id = NotificationData::orderBy('n_id', 'desc')->first();

    if (isset($request->filter_id)) {
        // Update the notification ID in notification_filter_data table
        $notification_filter_data = NotificationFilterData::find($request->filter_id);
        $notification_filter_data->n_id = $notify_Id->n_id;
        $notification_filter_data->save();

        // Delete rows with n_id = 0 in notification_filter_data table
        NotificationFilterData::where('n_id', 0)->delete();
    }

    $n_imgUploadType = $request->input('n_imgUploadType');
    $n_image_db = "";
    $n_image_old = $request->input('n_image_old');
    $n_image_url = $request->input('n_image_url');

    // Image upload logic
    if ($n_imgUploadType == "1") {
        if (!empty($n_image_old)) {
            $n_image_db = $n_image_old;
        } else {
            $img_upload_suc = $this->imageUpload($request, $notify_Id->n_id);
            if ($img_upload_suc["status"] == "1" || $img_upload_suc["status"] == 1) {
                $n_image_db = asset($img_upload_suc["file_path"]);
            } else {
                return back()->with('error_message', $img_upload_suc['message']);
            }
        }
    } elseif ($n_imgUploadType == "2") {
        $n_image_db = !empty($n_image_url) ? $n_image_url : $n_image_old;
    }

    // Update notification_data table with image info
    DB::table('notification_data')
        ->where('n_id', $notify_Id->n_id)
        ->update([
            'n_image' => $n_image_db,
            'n_img_upload_type' => $n_imgUploadType,
            'n_updated_at' => $cur_time
        ]);

    // Check if it's a template
    if ($is_template == 1) {
        DB::table('notification_template')->insert([
            'template_name' => $notify_Id->n_id . "-" . $n_title,
            'n_title' => $n_title,
            'n_text' => $n_text,
            'n_name' => $n_name,
            'n_image' => $n_image_db,
            'n_img_upload_type' => $n_imgUploadType,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // ============================================================
    // SEND TYPE 1: Immediate send (Queue-based, replaces old sync send)
    // ============================================================
    if ($send_type == "1") {

        if (empty($device_ids_list) || empty($app_ids_list)) {
            $setMsg = ['error_message' => 'Push Notification Failed!! Device Ids or App Ids Missing!!'];
            return back()->with($setMsg);
        }

        if ($device_ids_count == 0 || $device_ids_count == "") {
            $setMsg = ['message' => 'Push Notification Sending Failed!! Device Ids Count will be 0 !!'];
            return back()->with($setMsg);
        }

        $device_ids_list = unserialize($device_ids_list);

        $total_dispatched = 0;

        foreach ($device_ids_list as $app_id => $ids) {
            if ($app_id == "" || empty($ids)) {
                continue;
            }

            // Fetch full device rows (tokens, keys, etc.)
            $deviceDatas = $this->getDeviceIdsOne($notification_type, $ids, $app_id, $app_mode_p_d);

            if (empty($deviceDatas)) {
                continue;
            }

            $androidDevices = array_values(array_filter($deviceDatas, function ($d) {
                return $d['dev_type'] == 1 && $d['app_type_android'] == 'Android';
            }));

            // $iosDevices = array_values(array_filter($deviceDatas, function ($d) {
            //     return $d['dev_type'] == 2 && $d['app_type_ios'] == 'IOS';
            // }));

            $notifyFileJson = $deviceDatas[0]['notify_file_json'] ?? null;

            // Android: 500 tokens per job (FCM multicast limit)
            foreach (array_chunk($androidDevices, 500) as $chunk) {
                \App\Jobs\SendPushAndroidBatchJob::dispatch(
                    $notify_Id->n_id,
                    $chunk,
                    $app_mode_p_d,
                    $notifyFileJson
                );
                $total_dispatched += count($chunk);
            }

            // iOS: 50 devices per job (APNs is per-token, smaller chunk = more parallelism)
            // foreach (array_chunk($iosDevices, 50) as $chunk) {
            //     \App\Jobs\SendPushIOSBatchJob::dispatch($notify_Id->n_id, $chunk, $app_mode_p_d);
            //     $total_dispatched += count($chunk);
            // }
        }

        \Log::info('Push notification jobs dispatched', [
            'n_id' => $notify_Id->n_id,
            'total_dispatched' => $total_dispatched,
        ]);

        DB::table('notification_data')
            ->where('n_id', $notify_Id->n_id)
            ->update([
                'n_status' => 'In Progress',
                'n_msg' => 'Dispatched to queue (' . $total_dispatched . ' devices)',
                'n_updated_at' => now()
            ]);

        $setMsg = ['success_message' => 'Push Notification Dispatched to Queue Successfully'];
        return back()->with($setMsg);
    }

    // ============================================================
    // SEND TYPE 2: Scheduled send
    // ============================================================
    elseif ($send_type == "2") {
        if ($valid_schedule_time == 1) {
            $cur_time = now();

            DB::table('notification_data_query')->insert([
                'n_id' => $notify_Id->n_id,
                'query' => $device_exe_query,
                'created_at' => $cur_time
            ]);

            $updateResult = DB::table('notification_data')
                ->where('n_id', $notify_Id->n_id)
                ->update([
                    'schdedule_send' => 1,
                    'n_status' => 'Pending'
                ]);

            if ($updateResult) {
                $setMsg = ["success_message" => "Push Notification Sending (Scheduled Process Setup) Successfully"];
                return back()->with($setMsg);
            } else {
                $setMsg = ["error_message" => "Push Notification Sending Failed! Schedule Time error"];
                return back()->with($setMsg);
            }
        } else {
            $setMsg = ["error_message" => "Push Notification Sending Successfully.. Invalid Schedule Time"];
            return back()->with($setMsg);
        }
    }

    // ============================================================
    // Invalid send_type
    // ============================================================
    else {
        $setMsg = ["error_message" => "Push Notification Sending failed"];
        return back()->with($setMsg);
    }
}
//end new push notification
    private function imageUpload(Request $request, $notify_Id)
    {
        if ($request->hasFile('n_image') && $request->file('n_image')->isValid()) {
            $file = $request->file('n_image');
            $file_ext = strtolower($file->getClientOriginalExtension());
            $allowed_types = array('jpg', 'png', 'jpeg');
            $maxsize = 15 * 1024 * 1024; // Maximum file size (15MB)

            if (in_array($file_ext, $allowed_types)) {
                if ($file->getSize() > $maxsize) {
                    return array("status" => "0", "message" => "File size maximum 15MB allowed");
                }

                $change_img_name = "notify_image" . "_" . $notify_Id . "_" . time();
                $change_img_name = $change_img_name . '.' . $file_ext;
                $upload_dir = public_path('uploads/notify_img/');
                $filepath_for_db = 'uploads/notify_img/' . $change_img_name;
                $filepath = $upload_dir . $change_img_name;
                if ($file->move($upload_dir, $change_img_name)) {
                    return array("status" => "1", "message" => "File Uploading success", "file_path" => $filepath_for_db);
                } else {
                    return array("status" => "0", "message" => "File Uploading error!");
                }
            } else {
                return array("status" => "0", "message" => "JPG, PNG & JPEG file types only allowed!");
            }
        } else {
            return array("status" => "0", "message" => "Invalid file or file not found");
        }
    }

    // private function getDeviceIdsOne($notification_type,$device_ids, $app_id, $app_mode_p_d)
    // {
    //     $resDeviceids = [];

    //     if ($app_mode_p_d == 1) {
    //         //development
    //         $app_mode_p_d = 1;
    //     } elseif ($app_mode_p_d == 2) {
    //         //production
    //         $app_mode_p_d = 0;
    //     }

    //     if ($app_id != '' || $app_id != '0') {
    //         $appsList = DB::table('apps')->where('app_id', $app_id)->first();
    //         $app_table_name = DB::table('apps')->where('app_id', $app_id)->value('app_table_name');

    //         $ids = implode("','", $device_ids);
    //         $result = DB::table($app_table_name)
    //             ->join('apps', 'apps.app_id', '=', $app_table_name . '.dev_app_id')
    //             ->whereIn('dev_id', $device_ids)
    //             ->where('dev_app_id', $app_id)
    //             ->where($app_table_name . '.appMode', $app_mode_p_d) // add this line
    //             ->select(
    //                 $app_table_name . '.dev_id',
    //                 $app_table_name . '.dev_type',
    //                 $app_table_name . '.dev_gcm_id',
    //                 $app_table_name . '.appMode as appMode_p_d',
    //                 'apps.app_id',
    //                 'apps.android_api_key',
    //                 'apps.app_type_android',
    //                 'apps.app_type_ios',
    //                 'apps.ios_key_id',
    //                 'apps.ios_team_id',
    //                 'apps.ios_bundle_id'
    //             );
                
    //         if($notification_type == "1"){
    //             $result->where('normal_notification', 1);
    //         }
    //         if($notification_type == "2"){
    //             $result->where('read_notification', 1);
    //         }
    //         if($notification_type == "3"){
    //             $result->where('celebration_notification', 1);
    //         }
    //         if($notification_type == "4"){
    //             $result->where('deeplink_notification', 1);
    //         }
               
                
    //         $result = $result->get();

    //         foreach ($result as $row) {
    //             $resDeviceids[] = [
    //                 'dev_id' => $row->dev_id,
    //                 'device_gcm_id' => $row->dev_gcm_id,
    //                 'dev_type' => $row->dev_type,
    //                 'appMode_p_d' => $row->appMode_p_d,
    //                 'app_id' => $row->app_id,
    //                 'android_api_key' => $row->android_api_key,
    //                 'app_type_android' => $row->app_type_android,
    //                 'app_type_ios' => $row->app_type_ios,
    //                 'notify_file_json' => $appsList->notify_file_json,
    //                 'ios_team_id' => (!empty($row->ios_team_id) ? $this->getTeamId($row->ios_team_id) : '0'),
    //                 'ios_key_id' => (!empty($row->ios_key_id) ? $this->getKeyId($row->ios_key_id) : ['0', '0']),
    //                 'ios_bundle_id' => $row->ios_bundle_id,
    //             ];
    //         }

    //         return $resDeviceids;
    //     } else {
    //         return $resDeviceids;
    //     }
    // }
   private function getDeviceIdsOne($notification_type, $device_ids, $app_id, $app_mode_p_d)
  {
      $resDeviceids = [];
 
      if ($app_mode_p_d == 1) {
          //development
          $app_mode_p_d = 1;
      } elseif ($app_mode_p_d == 2) {
          //production
          $app_mode_p_d = 0;
      }
 
      if ($app_id != '' || $app_id != '0') {
          $appsList = DB::table('apps')->where('app_id', $app_id)->first();
          $app_table_name = DB::table('apps')->where('app_id', $app_id)->value('app_table_name');
 
          // FIXED: Chunk device IDs to avoid too many placeholders error
          // MySQL has a limit on placeholders in prepared statements
          // We'll process in chunks of 500 device IDs at a time
          $allResults = collect();
          $chunks = array_chunk($device_ids, 500);
 
          foreach ($chunks as $chunk) {
              $query = DB::table($app_table_name)
                  ->join('apps', 'apps.app_id', '=', $app_table_name . '.dev_app_id')
                  ->whereIn('dev_id', $chunk)
                  ->where('dev_app_id', $app_id)
                  ->where($app_table_name . '.appMode', $app_mode_p_d)
                  ->select(
                      $app_table_name . '.dev_id',
                      $app_table_name . '.dev_type',
                      $app_table_name . '.dev_gcm_id',
                      $app_table_name . '.appMode as appMode_p_d',
                      'apps.app_id',
                      'apps.android_api_key',
                      'apps.app_type_android',
                      'apps.app_type_ios',
                      'apps.ios_key_id',
                      'apps.ios_team_id',
                      'apps.ios_bundle_id'
                  );
 
              if ($notification_type == "1") {
                  $query->where('normal_notification', 1);
              }
              if ($notification_type == "2") {
                  $query->where('read_notification', 1);
              }
              if ($notification_type == "3") {
                  $query->where('celebration_notification', 1);
              }
              if ($notification_type == "4") {
                  $query->where('deeplink_notification', 1);
              }
 
              $chunkResults = $query->get();
              $allResults = $allResults->concat($chunkResults);
          }
 
          foreach ($allResults as $row) {
              $resDeviceids[] = [
                  'dev_id' => $row->dev_id,
                  'device_gcm_id' => $row->dev_gcm_id,
                  'dev_type' => $row->dev_type,
                  'appMode_p_d' => $row->appMode_p_d,
                  'app_id' => $row->app_id,
                  'android_api_key' => $row->android_api_key,
                  'app_type_android' => $row->app_type_android,
                  'app_type_ios' => $row->app_type_ios,
                  'notify_file_json' => $appsList->notify_file_json,
                  'ios_team_id' => (!empty($row->ios_team_id) ? $this->getTeamId($row->ios_team_id) : '0'),
                  'ios_key_id' => (!empty($row->ios_key_id) ? $this->getKeyId($row->ios_key_id) : ['0', '0']),
                  'ios_bundle_id' => $row->ios_bundle_id,
              ];
          }
 
          return $resDeviceids;
      } else {
          return $resDeviceids;
      }
  }
    function setupPushNotifyOne($notify_datas, $device_datas, $count_array, $app_mode_p_d)
    {
        $dev_count = 0;
        $app_id = 0;
        foreach ($device_datas as $device_data) {
            $notify_Id = $notify_datas['n_id'];
            $app_id = $device_data['app_id'];
            if ($device_data['dev_type'] == 1 && $device_data['app_type_android'] == 'Android') {
                $unique_noti_id = strval($notify_Id);

                $data = [
                    'n_text' => $notify_datas['n_text'],
                    'n_title' => $notify_datas['n_title'],
                    'n_image' => $notify_datas['n_image'],
                    'registrationIds' => $device_data['device_gcm_id'],
                    'API_ACCESS_KEY' => $device_data['android_api_key'],
                    'notify_file_json' => $device_data['notify_file_json'],
                    'uniqueID' => $unique_noti_id,
                    'notificationType' => $notify_datas['notification_type'],
                    'deeplink_url' => $notify_datas['deeplink_url'],
                    'ed' => $notify_datas['ed'],
                    'pd' => $notify_datas['pd'],
                    'book_num' => $notify_datas['book_num'],
                    'chapter_num' => $notify_datas['chapter_num'],
                    'verse_num' => $notify_datas['verse_num']
                ];

                $noti_msg = $this->sendpushnotify_android($data);

                // New success / failure check
                $send_status = $noti_msg['status'] == "1" ? 'Completed' : 'Not Completed';
                
                if ($noti_msg['status'] != "1") {
                    NotificationSendData::create([
                        'n_id'             => $notify_Id,
                        'app_id'           => $app_id,
                        'dev_id'           => $device_data['dev_id'],
                        'dev_type'         => 1,
                        'app_mode_p_d'     => $app_mode_p_d,
                        'status'           => $send_status,
                        'curl_msg'         => $noti_msg['message'], // updated field
                        'http_status_code' => $noti_msg['result'],  // 400 on error
                    ]);
                }
                
                // Count only success
                $dev_count += $noti_msg['status'] == "1" ? 1 : 0;

            }

            if ($device_data['dev_type'] == 2 && $device_data['app_type_ios'] == 'IOS') {
                $unique_noti_id = strval($notify_Id);
                $data = [
                    'n_text' => $notify_datas['n_text'],
                    'n_title' => $notify_datas['n_title'],
                    'n_image' => $notify_datas['n_image'],
                    'registrationIds' => $device_data['device_gcm_id'],
                    'app_mode_p_d' => $app_mode_p_d,
                    'ios_key_id' => $device_data['ios_key_id'],
                    'ios_key_id_p8file' => $device_data['ios_key_id'][1],
                    'ios_team_id' => $device_data['ios_team_id'],
                    'ios_bundle_id' => $device_data['ios_bundle_id'],
                    'uniqueID' => $unique_noti_id,
                    'notificationType' => $notify_datas['notification_type'],
                    'deeplink_url' => $notify_datas['deeplink_url'],
                    'ed' => $notify_datas['ed'],
                    'pd' => $notify_datas['pd'],
                    'book_num' => $notify_datas['book_num'],
                    'chapter_num' => $notify_datas['chapter_num'],
                    'verse_num' => $notify_datas['verse_num']
                ];

                $noti_msg = $this->sendpushnotify_iOS($data);
                $send_status = $noti_msg['http_status'] == 200 ? 'Completed' : 'Not Completed';

                if ($noti_msg['http_status'] != 200) {
                    NotificationSendData::create([
                        'n_id' => $notify_Id,
                        'app_id' => $app_id,
                        'dev_id' => $device_data['dev_id'],
                        'dev_type' => 2,
                        'app_mode_p_d' => $app_mode_p_d,
                        'status' => $send_status,
                        'curl_msg' => $noti_msg['curl_msg'],
                        'http_status_code' => $noti_msg['http_status']
                    ]);
                }

                $dev_count += $noti_msg['http_status'] == 200 ? 1 : 0;
            }
        }

        $total_device_count = count($device_datas);
        $count_array['total_device_count_fail'] = abs($total_device_count - $dev_count);
        $count_array['total_device_count_suc'] = $dev_count;

        $count_array[] = [
            'app_id' => $app_id,
            'total_device_count' => $total_device_count,
            'device_count_suc' => $dev_count,
            'device_count_fail' => abs($total_device_count - $dev_count)
        ];

        return $count_array;
    }

    // private function sendpushnotify_android($data)
    // {

    //     // // Notification payload (replace with actual data)
    //     // $fields = array(
    //     //     "message" => array(
    //     //         "token" => "eaMuOMMXQb6CBzEMFOljjg:APA91bHlwgOoXav8-Kz1EVdGMrscwCxH1-pgCbjWe7Ny4mttxyR1j-1Te7vtpRKbf-52sAC0PXT-9smUpM8oiZDabENwU-UG5Erh--8cML1MUpnxDK8rpbb6U02aQDk6PZMKeVvalAPr", // FCM registration token
    //     //         "notification" => array(
    //     //             "title" => 'Test Notification',
    //     //             "body" => "This is a test message",
    //     //         ),
    //     //         "android" => array(
    //     //             "priority" => "high"
    //     //         )
    //     //     )
    //     // );

    //     // // Encode payload as JSON
    //     // $jsonPayload = json_encode($fields, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    //     // // Set headers for request
    //     // $headers = array(
    //     //     'Content-Type: application/json',
    //     //     'Authorization: Bearer ' . $accessToken
    //     // );

    //     // $project_id = "skyraan-android-bible-1";  // Replace with your actual Firebase project ID

    //     // $url = 'https://fcm.googleapis.com/v1/projects/' . $project_id . '/messages:send';

    //     // // Initialize cURL session
    //     // $ch = curl_init();
    //     // curl_setopt($ch, CURLOPT_URL, $url); // Replace {project_id}
    //     // curl_setopt($ch, CURLOPT_POST, true);
    //     // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //     // curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);

    //     // // Execute request
    //     // $result = curl_exec($ch);
    //     // $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    //     // // Handle result and possible errors
    //     // if ($result === FALSE) {
    //     //     $resOfPush = array(
    //     //         "status" => "0",
    //     //         "message" => "CURL Error: " . curl_error($ch),
    //     //         "http_status" => $httpStatusCode
    //     //     );
    //     // } else {
    //     //     $resOfPush = array(
    //     //         "status" => "1",
    //     //         "message" => "Notification Sent Successfully",
    //     //         "result" => $result,
    //     //         "http_status" => $httpStatusCode
    //     //     );
    //     // }
    //     // dd($resOfPush);


    //     // dd($data);
    //     $notificationType   = strval($data['notificationType']);
    //     $uniqueID           = strval($data['uniqueID']);
    //     $deeplink_url       = strval($data['deeplink_url']);
    //     $ed                 = strval($data['ed']);
    //     $pd                 = strval($data['pd']);

    //     $is_remote_notify   = strval("1");
    //     $book_number        = strval($data['book_num']);
    //     $chapter_number     = strval($data['chapter_num']);
    //     $verse_number       = strval($data['verse_num']);

    //     $n_title            = (($data['n_title']));
    //     $n_msg              = ((($data['n_text'])));

    //     $n_msg              = (html_entity_decode(($n_msg)));

    //     $n_image            = (strval($data['n_image']));

    //     $API_ACCESS_KEY = $data['API_ACCESS_KEY'];
    //     $registrationIds = array($data['registrationIds']);

    //     // Path to your service account JSON file
    //     $serviceAccountFile = public_path($data['notify_file_json']); // Update path as needed
    
    //     // Scopes for FCM
    //     $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

    //     // Generate OAuth 2.0 token using ServiceAccountCredentials
    //     $credentials = new ServiceAccountCredentials($scopes, $serviceAccountFile);
    //     $authToken = $credentials->fetchAuthToken();
    //     $accessToken = $authToken['access_token'];

    //     // Notification payload
    //     $fields = array(
    //         "message" => array(
    //             "token" => $registrationIds[0], // Use a comma-separated string of registration IDs if needed
    //             "notification" => array(
    //                 "title" => $n_title,
    //                 "body" => $n_msg,
    //                 'image' => $n_image
    //             ),
    //             "android" => array(
    //                 "priority" => "high"
    //             ),
    //             "data" => array(
    //                 'body'              => $n_msg,
    //                 'title'             => $n_title,
    //                 'image'             => $n_image,
    //                 'sound'             => 'default',
    //                 'icon'              => 'icon',
    //                 'notificationType'  => $notificationType,
    //                 'uniqID'            => $uniqueID,
    //                 'deeplink_url'      => $deeplink_url,
    //                 'ed'                => $ed,
    //                 'pd'                => $pd,
    //                 'is_remote_notify'  => $is_remote_notify,
    //                 'booknumber'        => $book_number,
    //                 'chapternumber'     => $chapter_number,
    //                 'versenumber'       => $verse_number,
    //             )
    //         )
    //     );

    //     $json_encode_arr = json_encode($fields, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    //     // Set headers for request
    //     $headers = array(
    //         'Content-Type: application/json; charset=UTF-8',
    //         'Authorization: Bearer ' . $accessToken
    //     );

    //     $project_id = "skyraan-android-bible-1";  // Replace with your actual Firebase project ID

    //     $url = 'https://fcm.googleapis.com/v1/projects/' . $project_id . '/messages:send';

    //     // Initialize cURL session
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url); // Replace {project_id}
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $json_encode_arr);

    //     $result = curl_exec($ch);
    //     $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    //     if ($http_status_code !== 200) {
    //         $resOfpush = array("status" => "0", "curl_msg" => "CURL Result:" . $result . " CURL Error:" . curl_error($ch), "http_status" => $http_status_code);
    //     } else {
    //         $resOfpush = array("status" => "1", "curl_msg" => "CURL Result:" . $result . " CURL Error:" . curl_error($ch), "http_status" => $http_status_code);
    //     }

    //     curl_close($ch);

    //     return $resOfpush;
    // }
    
    private function sendpushnotify_android($data)
    {
        // Extract required fields
        $notificationType   = (string) $data['notificationType'];
        $uniqueID           = (string) $data['uniqueID'];
        $deeplink_url       = (string) $data['deeplink_url'];
        $ed                 = (string) $data['ed'];
        $pd                 = (string) $data['pd'];
    
        $is_remote_notify   = "1";
        $book_number        = (string) $data['book_num'];
        $chapter_number     = (string) $data['chapter_num'];
        $verse_number       = (string) $data['verse_num'];
    
        $n_title            = (string) $data['n_title'];
        $n_msg              = html_entity_decode((string) $data['n_text']);
        $n_image            = (string) $data['n_image'];
    
        $deviceToken        = $data['registrationIds'];  // Single token
        
        // Path to service account JSON file
        $serviceAccountFile = public_path($data['notify_file_json']);
        // dd([
        //     'notify_file_json' => $data['notify_file_json'],
        //     'path' => $serviceAccountFile,
        //     'is_dir' => is_dir($serviceAccountFile),
        //     'is_file' => is_file($serviceAccountFile),
        // ]);
    
        // Initialize Firebase
        $firebase = (new \Kreait\Firebase\Factory)->withServiceAccount($serviceAccountFile);
        $messaging = $firebase->createMessaging();
        // \Log::info($serviceAccountFile);
        // Create message object
        $message = \Kreait\Firebase\Messaging\CloudMessage::withTarget('token', $deviceToken)
            ->withNotification([
                'title' => $n_title,
                'body'  => $n_msg,
                'image' => $n_image,
            ])
            ->withData([
                'body'              => $n_msg,
                'title'             => $n_title,
                'image'             => $n_image,
                'sound'             => 'default',
                'icon'              => 'icon',
                'notificationType'  => $notificationType,
                'uniqID'            => $uniqueID,
                'deeplink_url'      => $deeplink_url,
                'ed'                => $ed,
                'pd'                => $pd,
                'is_remote_notify'  => $is_remote_notify,
                'booknumber'        => $book_number,
                'chapternumber'     => $chapter_number,
                'versenumber'       => $verse_number,
            ])
            ->withAndroidConfig([
                'priority' => 'high',
            ]);
    
        // Try sending
        try {
            $response = $messaging->send($message);
    
            $resOfPush = [
                "status"  => "1",
                "message" => "Notification Sent Successfully",
                "result"  => $response,
            ];
    
            // Log result
            \Log::info('Android Push Sent', [
                'token'    => $deviceToken,
                'response' => json_encode($response),
            ]);
    
        } catch (\Kreait\Firebase\Exception\MessagingException $e) {
    
            $resOfPush = [
                "status"  => "0",
                "message" => $e->getMessage(),
                "result"  => 400,
            ];
    
            \Log::error('Android Push Failed', [
                'token'    => $deviceToken,
                'response' => $e->getMessage(),
            ]);
    
        } catch (\Exception $e) {
    
            $resOfPush = [
                "status"  => "0",
                "message" => $e->getMessage(),
                "result"  => 400,
            ];
    
            \Log::error('Android Push Failed', [
                'token'    => $deviceToken,
                'response' => $e->getMessage(),
            ]);
        }
    
        return $resOfPush;
    }



    private function sendpushnotify_iOS($data)
    {
        // dd($data);
        //  development url, or use http://api.development.push.apple.com for development environment
        //  production url, or use http://api.push.apple.com for production environment
        if($data['app_mode_p_d'] == 2 ){
            //development
            $url = 'https://api.development.push.apple.com';
        }
        elseif($data['app_mode_p_d'] == 1){
            //production
            $url = 'https://api.push.apple.com';
        }
        // dd($data);
        $keyfile = $data['ios_key_id_p8file'];
        $keyid = $data['ios_key_id'][0];
        $teamid = $data['ios_team_id'];
        $bundleid = $data['ios_bundle_id'];
        

        // Device Token
        $token = $data['registrationIds'];

        $notificationType   = strval($data['notificationType']);
        $uniqueID           = strval($data['uniqueID']);
        $deeplink_url       = strval($data['deeplink_url']);
        $ed                 = strval($data['ed']);
        $pd                 = strval($data['pd']);
        $is_remote_notify   = strval("1");
        $book_number        = strval($data['book_num']);
        $chapter_number     = strval($data['chapter_num']);
        $verse_number       = strval($data['verse_num']);

        $n_title            = (html_entity_decode($data['n_title']));
        $n_msg              = (html_entity_decode(($data['n_text'])));

        $payloadArray =
        [
            'aps' => [
                  'alert' => [
                    'title' =>  $n_title,// title of the notification
                    'body' => (($n_msg))// content/body of the notification
                  ],
            'mutable-content' => "1"
        ],
        'media_attachment' => $data['n_image'],
        'notificationType' => $notificationType,
        'uniqID' => $uniqueID,
	    'deeplink_url' => $deeplink_url,
    	'ed' => $ed,
    	'pd' => $pd,
        'is_remote_notify'  => ($is_remote_notify),
    	'book_num'        => ($book_number),
    	'chapter_num'     => ($chapter_number),
    	'verse_num'       => ($verse_number)
        ];
        // dd($payloadArray);
        // Encode it as json.
        $message = json_encode($payloadArray);

        $keyfile = $this->curl_get_file_contents($keyfile);

        $key = openssl_pkey_get_private($keyfile);

        $header = ['alg'=>'ES256','kid'=>$keyid];
        $claims = ['iss'=>$teamid,'iat'=>time()];

        $header_encoded = $this->base64($header);
        $claims_encoded = $this->base64($claims);


        $signature = '';
        openssl_sign($header_encoded . '.' . $claims_encoded, $signature, $key, 'sha256');
        $jwt = $header_encoded . '.' . $claims_encoded . '.' . base64_encode($signature);

        // only needed for PHP prior to 5.5.24
        if (!defined('CURL_HTTP_VERSION_2_0')) {
            define('CURL_HTTP_VERSION_2_0', 3);
        }

        $http2ch = curl_init();
        curl_setopt_array($http2ch, array(
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
            CURLOPT_URL => "$url/3/device/$token",
            CURLOPT_PORT => 443,
            CURLOPT_HTTPHEADER => array(
                "apns-topic: {$bundleid}",
                "authorization: bearer $jwt"
            ),
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $message,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HEADER => 1
        ));


        $resOfpush=array();
        $result = curl_exec($http2ch);
    dd($result);
        $http_status_code = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);

        if($result === FALSE)
        {
            $resOfpush= array("status"=>"0","curl_msg"=> "CURL Result:".$result." CURL Error:".curl_error($http2ch),"http_status"=>$http_status_code);
            // throw new Exception("Curl failed: ".curl_error($http2ch));
        }
        else
        {
            $resOfpush= array("status"=>"1","curl_msg"=> "CURL Result:".$result." CURL Error:".curl_error($http2ch),"http_status"=>$http_status_code);
        }

        return $resOfpush;
    }

    function curl_get_file_contents($URL)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        curl_close($c);

        if ($contents) return $contents;
        else return FALSE;
    }

    function base64($data)
    {
        return rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
    }

    private function insert_into_inprogress_all_apps($n_id, $app_ids, $device_ids)
    {
        $dev_count_total = count($device_ids);
        $modulo = 100;
        [$quotient, $remainder] = $this->modBigNumber((string)$dev_count_total, (string)$modulo);
        $insertValues = [];

        if ($quotient != 0 || $quotient != '') {
            for ($i = 0; $i < $quotient; $i++) {
                $start_a = $i * $modulo;
                $temp_arr = array_slice($device_ids, $start_a, $modulo);
                $temp_count = count($temp_arr);
                $temp_arr_2 = array_slice($app_ids, $start_a, $modulo);

                $notificationData = [
                    'n_id' => $n_id,
                    'app_id' => serialize($temp_arr_2),
                    'device_ids' => serialize($temp_arr),
                    'total_count' => $temp_count,
                    'created_at' => now(), // Assuming `created_at` is a timestamp field
                    'status' => 'In Progress'
                ];

                NotificationSendingData::insert($notificationData);
                $insertValues[] = $notificationData;
            }
        }

        if ($remainder != 0 || $remainder != '') {
            $temp_arr = array_slice($device_ids, -($remainder));
            $temp_arr_2 = array_slice($app_ids, -($remainder));
            $temp_count = count($temp_arr);

            $notificationData = [
                'n_id' => $n_id,
                'app_id' => serialize($temp_arr_2),
                'device_ids' => serialize($temp_arr),
                'total_count' => $temp_count,
                'created_at' => now(), // Assuming `created_at` is a timestamp field
                'status' => 'In Progress'
            ];

            NotificationSendingData::insert($notificationData);
            $insertValues[] = $notificationData;
        }
    }


	private function modBigNumber($num, $m)
    {
        // of big number
        $vec;
        $x = 0;
        $mod = 0;
        $q = 0;
        // Do step by
        // step division
        for ($i = 0;$i < strlen($num); $i++)
        {
            $digit = $num[$i] - '0';

            // Update modulo
            // by concatenating
            // current digit.
            $mod = $mod * 10 + $digit;

            // Update quotient
            $quo = (int)($mod / $m);
            $vec[$x++] = $quo;

            // Update mod for
            // next iteration.
            $mod = $mod % $m;
        }
        // Flag used to
        // remove starting zeros
        $zeroflag = 0;
        for ($i = 0; $i < $x; $i++)
        {
            if ($vec[$i] == 0 &&
                $zeroflag == 0)
                continue;
            $zeroflag = 1;
            $q .=$vec[$i];
        }

        return array($q,$mod);
    }


    function getTeamId($ios_team_id_temp)
    {
        $team = iosTeamId::where('id', $ios_team_id_temp)->first();

        return $team ? $team->team_id : 0;
    }

    function getKeyId($ios_key_id_temp)
    {
        $key = iosKeyId::where('id', $ios_key_id_temp)->first();

        return $key ? [$key->key_id, $key->p8_file] : ['0', '0'];
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
            $getAppsDetails = App::whereAppId($id)->first();
            $results = App::select('app_id', 'app_name', 'app_type_android', 'app_type_ios', 'app_type_version')
                            ->where('app_id', '!=', $id)
                            ->where('app_mapping_permission', '1')
                            ->where('app_deleted', '0')
                            ->get();
            return view('admin.pushnotify.mapped_app_category', compact('getAppsDetails','results'));
        }
        else
        {
            return redirect()->route('admin.dashboard')->with("error","Access Denied");
        }

    }

    public function view(Request $request, $id)
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
            if($request->ajax())
            {
                $app_id = $id;
                $app = App::where('app_id', $app_id)->first();
                $app_table_name = $app->app_table_name;

                $table = $app_table_name;
                $primaryKey = 'dev_id';

                $columns = [
                    ['data' => 'dev_id', 'name' => 'dev_id'],
                    ['data' => 'dev_app_id', 'name' => 'dev_app_id', 'render' => function ($data) {
                        return empty($data) ? '-' : $data;
                    }],
                    ['data' => 'appDeviceId', 'name' => 'appDeviceId', 'render' => function ($data) {
                        return empty($data) ? '-' : $data;
                    }],
                    ['data' => 'dev_type', 'name' => 'dev_type', 'render' => function ($data) {
                        return empty($data) ? '-' : $data;
                    }],
                    ['data' => 'appMode', 'name' => 'appMode'],
                    ['data' => 'app_name', 'name' => 'app_name', 'render' => function ($data) {
                        return empty($data) ? '-' : $data;
                    }],
                    ['data' => 'app_version', 'name' => 'app_version', 'render' => function ($data) {
                        return empty($data) ? '-' : $data;
                    }],
                    [
                        'data' => 'activity_time',
                        'name' => 'activity_time',
                        'render' => function ($data) {
                            $last7Days = [];

                            for ($i = 0; $i < 7; $i++) {
                                $last7Days[] = date('Y-m-d', strtotime("-$i days"));
                            }

                            if (!empty($data)) {
                                $date = date('Y-m-d', strtotime($data));

                                if (in_array($date, $last7Days)) {
                                    return '<button class="btn btn-success btn-sm">Active</button>';
                                } else {
                                    return '<button class="btn btn-danger btn-sm">Inactive</button>';
                                }
                            } else {
                                return 'Nil';
                            }
                        }
                    ],
                    ['data' => 'language', 'name' => 'language', 'render' => function ($data) {
                        return empty($data) ? '-' : $data;
                    }],
                    ['data' => 'country_code', 'name' => 'country_code', 'render' => function ($data) {
                        return empty($data) ? '-' : $data;
                    }],
                    ['data' => 'dev_version', 'name' => 'dev_version', 'render' => function ($data) {
                        return empty($data) ? '-' : $data;
                    }],
                    ['data' => 'latitude', 'name' => 'latitude', 'render' => function ($data) {
                        return empty($data) ? '-' : $data;
                    }],
                    ['data' => 'longitude', 'name' => 'longitude', 'render' => function ($data) {
                        return empty($data) ? '-' : $data;
                    }],
                    ['data' => 'dev_gcm_id', 'name' => 'dev_gcm_id', 'render' => function ($data) {
                        return empty($data) ? '-' : $data;
                    }],
                    ['data' => 'created_at', 'name' => 'created_at', 'render' => function ($data) {
                        return empty($data) ? '-' : $data;
                    }],
                ];

                return DataTables::eloquent(App::query()->from($table))->toJson();
            }

            $app_id = $id;
            $app = App::where('app_id', $app_id)->first();
            $app_table_name = $app->app_table_name;

            $table = $app_table_name;
            $primaryKey = 'dev_id';

            $data = DB::table($table)
                ->select('dev_id', 'dev_app_id', 'appDeviceId', 'dev_type', 'appMode', 'app_name', 'app_version', 'activity_time', 'language', 'country_code', 'dev_version', 'latitude', 'longitude', 'dev_gcm_id', 'created_at')
                ->get();

            foreach ($data as $row) {
                $row->dev_app_id = empty($row->dev_app_id) ? '-' : $row->dev_app_id;
                $row->app_id = $app_id;
                $row->appDeviceId = empty($row->appDeviceId) ? '-' : $row->appDeviceId;
                $row->dev_type = empty($row->dev_type) ? '-' : $row->dev_type;
                $row->app_name = empty($row->app_name) ? '-' : $row->app_name;
                $row->app_version = empty($row->app_version) ? '-' : $row->app_version;
                $row->activity_time = empty($row->activity_time) ? 'Nil' : ($row->activity_time >= date('Y-m-d', strtotime('-7 days')) ? '<button class="btn btn-success btn-sm">Active</button>' : '<button class="btn btn-danger btn-sm">Inactive</button>');
                $row->language = empty($row->language) ? '-' : $row->language;
                $row->country_code = empty($row->country_code) ? '-' : $row->country_code;
                $row->dev_version = empty($row->dev_version) ? '-' : $row->dev_version;
                $row->latitude = empty($row->latitude) ? '-' : $row->latitude;
                $row->longitude = empty($row->longitude) ? '-' : $row->longitude;
                $row->dev_gcm_id = empty($row->dev_gcm_id) ? '-' : $row->dev_gcm_id;
                $row->created_at = empty($row->created_at) ? '-' : $row->created_at;
            }

            return view('admin.pushnotify.view_app_list', compact('data','app_id'));
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
            return view('admin.pushnotify.edit_app_list', $compact);
        }
        else
        {
            return redirect()->route('admin.dashboard')->with("error","Access Denied");
        }
    }

    public function EditAppList(Request $request, $id)
    {
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
}
