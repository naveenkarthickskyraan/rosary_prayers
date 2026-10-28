<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\NotificationSendingData;
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
use App\Models\NotificationSendData;
use App\Models\QuotesAppList;
use App\Models\Language;
use App\Models\PushNotifyApp;
use App\Models\App;
use App\Models\Module;
Use App\Models\Admin;

class ScheduledCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Scheduled:Cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'every days to check pushnotification send to user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $server_cur_date = Carbon::now()->format('Y-m-d');
        $sql = "SELECT * FROM `notification_data` WHERE `send_type` = '2' AND `schdedule_send` = '1' AND `n_status` LIKE 'Pending' AND `s_date`='$server_cur_date'  ORDER BY `n_id` DESC LIMIT 1";

        $main_res = DB::select($sql);
        if (count($main_res) > 0) {
            foreach ($main_res as $row) {
                $n_id = $row->n_id;
                $s_date = $row->s_date;
                $s_time = $row->s_time;

                $server_cur_time = Carbon::now()->timezone('Asia/Kolkata')->format('H:i');

                $EXTRA_time = 90;

                if (
                    (strtotime($server_cur_time) == strtotime($s_time)) ||
                    ((strtotime($s_time) <= strtotime($server_cur_time)) && (strtotime($server_cur_time) <= (strtotime($s_time) + $EXTRA_time)))
                ) {
                    $setup_time = 60;
                    $cron_time_per = 60;

                    $n_status_arr = ['status' => '', 'msg' => ''];

                    $device_ids_list = unserialize($row->target_dev);

                    $app_mode_p_d = $row->app_mode_p_d;
                    $device_ids_count = $row->device_total_count;

                    $updated_dev_data = $this->getUpdatedDevList($n_id);

                    if ($updated_dev_data['res'] == true) {
                        $device_ids_list = $updated_dev_data['target_dev'];
                        $app_mode_p_d = $app_mode_p_d;
                        $device_ids_count = $updated_dev_data['device_total_count'];

                        DB::table('notification_data')
                            ->where('n_id', $n_id)
                            ->update(['target_dev' => $device_ids_list, 'device_total_count' => $device_ids_count, 'n_updated_at' => Carbon::now()]);

                        $device_ids_list = unserialize($device_ids_list);
                    }


                    $n_text = $row->n_text;
                    $n_title = $row->n_title;
                    $n_image_db = $row->n_image;
                    $notification_type = $row->notification_type ?? 1;

                    $set_book_num = 0;
                    $set_chapter_num = 0;
                    $set_verse_num = 0;
                    $set_deeplink_url = '';
            
                    if ($notification_type == "2") {
                        $set_book_num = $row->book_num;
                        $set_chapter_num = $row->chapter_num;
                        $set_verse_num = $row->verse_num;
                    }
            
                    if ($notification_type == "4") {
                        $set_deeplink_url = $row->deeplink_url;
                    }
                    
                    $notify_datas = [
                        'n_id' => $n_id,
                        'n_text' => $n_text,
                        'n_title' => $n_title,
                        'n_image' => $n_image_db,
                        'notification_type' => strval($notification_type),
                        'book_num' => strval($set_book_num),
                        'chapter_num' => strval($set_chapter_num),
                        'verse_num' => strval($set_verse_num),
                        'deeplink_url' => strval($set_deeplink_url),
                        'ed' => '',
                        'pd' => ''
                    ];

                    $send_app_array = [];
                    $total_device_count = 0;
                    $total_device_count_fail = 0;
                    $total_device_count_suc = 0;

                    $temp_count_array = [];
                    $count_array = ['total_app_count' => count($send_app_array), 'total_device_count' => '0', 'total_device_count_suc' => '0', 'total_device_count_fail' => '0'];
                    $sending_process = false;

                    if (!empty($row->target_dev)) {
                        if ($device_ids_count == 0 || $device_ids_count == "") {
                            $n_status_arr['status'] = 'Not completed';
                            $n_status_arr['msg'] = 'Device Count will be 0';
                        } else {
                            if (25 >= $device_ids_count) {
                                foreach ($device_ids_list as $app_id => $device_ids) {
                                    if ($app_id!= "" &&!empty($device_ids)) {
                                        $device_datas = $this->getDeviceIdsOne($notification_type,$device_ids, $app_id, $app_mode_p_d);
                                        $total_device_count += count($device_datas);
                                        $count_array['total_device_count'] = $total_device_count;

                                        if (count($device_datas) == 0) {
                                            $n_status_arr['status'] = 'Not Completed';
                                            $n_status_arr['msg'].= 'App Id:'. $app_id. ' Device count will be 0';
                                        } else {
                                            $count_array =  $this->setupPushNotifyOne($notify_datas, $device_datas, $count_array, $app_mode_p_d);
                                        }
                                    } else {
                                        $n_status_arr['status'] = 'Not Completed';
                                        $n_status_arr['msg'] = 'App ids or Device ids empty';
                                    }
                                }
                            } else {
                                foreach ($device_ids_list as $app_id => $device_ids) {
                                    if ($app_id!= "" &&!empty($device_ids)) {
                                        $app_ids_fill = array_fill(0, count($device_ids), $app_id);
                                        $this->insert_into_inprogress_all_apps($n_id, $app_ids_fill, $device_ids);
                                        $sending_process = true;
                                    } else {
                                        $n_status_arr['status'] = 'Not Completed';
                                        $n_status_arr['msg'] ='App ids or Device ids empty';
                                    }
                                }
                            }
                        }
                    } else {
                        $n_status_arr['status'] = 'Not completed';
                        $n_status_arr['msg'] = 'Target Device data empty';
                    }

                    if ($sending_process) {
                        $n_status_arr['status'] = 'In Progress';
                        $n_status_arr['msg'] = 'Processing Device total count exceeded from 5';
                    } else {
                        if (($count_array['total_device_count'] == $count_array['total_device_count_suc'])) {
                            $n_status_arr['status'] = 'Completed ( '. $count_array['total_device_count_suc'] .' / '. $count_array['total_device_count'].')';
                            $n_status_arr['msg'] = 'All devices sending success';
                        } else {
                            $n_status_arr['status'] = 'Completed ( '. $count_array['total_device_count_suc'] .' / '. $count_array['total_device_count'].')';
                            $n_status_arr['msg'] = 'Some devices sending failed';
                        }
                    }

                    $n_status = $n_status_arr['status'];
                    $n_msg = $n_status_arr['msg'];
                    DB::table('notification_data')->where('n_id', $n_id)->update(['n_status' => $n_status, 'n_msg' => $n_msg, 'n_updated_at' => Carbon::now()]);
                    return $setMsg = ["success_message" => "Push Notification Sending (Scheduled Process Setup) Successfully"];

                }else{
                    $n_status_arr['status'] = 'error';
                    $n_status_arr['msg'] = 'Error From schedule cron job';
                    return $n_status_arr;
                }
            }
        }
    }


    function getDeviceIdsOne($notification_type, $device_ids, $app_id, $app_mode_p_d)
    {
        $resDeviceids = [];

        if ($app_mode_p_d == 1) {
            //development
            $app_mode_p_d = 1;
        } elseif ($app_mode_p_d == 2) {
            //production
            $app_mode_p_d = 0;
        }


        if (!empty($app_id)) {
            $appsList = DB::table('apps')->where('app_id', $app_id)->first();
            $app = App::where('app_id', $app_id)->first();

            if ($app) {
                $app_table_name = $app->app_table_name;

                $ids = implode("','", $device_ids);

                $devices = DB::table($app_table_name)
                    ->join('apps', $app_table_name . '.dev_app_id', '=', 'apps.app_id')
                    ->whereIn($app_table_name . '.dev_id', $device_ids)
                    ->where($app_table_name . '.appMode', $app_mode_p_d)
                    ->select(
                        $app_table_name . '.dev_id',
                        $app_table_name . '.dev_gcm_id',
                        $app_table_name . '.dev_type',
                        $app_table_name . '.appMode as appMode_p_d',
                        'apps.app_id',
                        'apps.android_api_key',
                        'apps.app_type_android',
                        'apps.app_type_ios',
                        'apps.ios_key_id',
                        'apps.ios_team_id',
                        'apps.ios_bundle_id'
                    );
                
                if($notification_type == "1"){
                    $devices->where('normal_notification', 1);
                }
                if($notification_type == "2"){
                    $devices->where('read_notification', 1);
                }
                if($notification_type == "3"){
                    $devices->where('celebration_notification', 1);
                }
                if($notification_type == "4"){
                    $devices->where('deeplink_notification', 1);
                }
                   
                    
                $devices = $devices->get();

                foreach ($devices as $device) {
                    $resDeviceids[] = [
                        'dev_id' => $device->dev_id,
                        'device_gcm_id' => $device->dev_gcm_id,
                        'dev_type' => $device->dev_type,
                        'appMode_p_d' => $device->appMode_p_d,
                        'app_id' => $device->app_id,
                        'android_api_key' => $device->android_api_key,
                        'notify_file_json' => $appsList->notify_file_json,
                        'app_type_android' => $device->app_type_android,
                        'app_type_ios' => $device->app_type_ios,
                        'ios_team_id' => !empty($device->ios_team_id) ? $this->getTeamId($device->ios_team_id) : '0',
                        'ios_key_id' => !empty($device->ios_key_id) ? $this->getKeyId($device->ios_key_id)[0] : '0',
                        'ios_key_id_p8file' => !empty($device->ios_key_id) ? $this->getKeyId($device->ios_key_id)[1] : '0',
                        'ios_bundle_id' => $device->ios_bundle_id
                    ];
                }
            }
        }

        return $resDeviceids;
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
                    'app_mode_p_d' => $app_mode_p_d,
                    'ios_key_id' => $device_data['ios_key_id'],
                    'ios_key_id_p8file' => $device_data['ios_key_id_p8file'],
                    'ios_team_id' => $device_data['ios_team_id'],
                    'ios_bundle_id' => $device_data['ios_bundle_id'],
                    'uniqueID' => $unique_noti_id,
                    'notificationType' => isset($notify_datas['notification_type']) ? $notify_datas['notification_type'] : 1,
                    'deeplink_url' => isset($notify_datas['deeplink_url']) ? $notify_datas['deeplink_url'] : "",
                    'ed' => isset($notify_datas['ed']) ? $notify_datas['ed'] : "",
                    'pd' =>  isset($notify_datas['pd']) ? $notify_datas['pd'] : "",
                    'book_num' => isset($notify_datas['book_num']) ? $notify_datas['book_num'] :  "",
                    'chapter_num' => isset($notify_datas['chapter_num']) ? $notify_datas['chapter_num'] :  "",
                    'verse_num' => isset($notify_datas['verse_num']) ? $notify_datas['verse_num'] :  "",
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
                    'ios_key_id_p8file' => $device_data['ios_key_id_p8file'],
                    'ios_team_id' => $device_data['ios_team_id'],
                    'ios_bundle_id' => $device_data['ios_bundle_id'],
                    'uniqueID' => $unique_noti_id,
                    'notificationType' => isset($notify_datas['notification_type']) ? $notify_datas['notification_type'] : 1,
                    'deeplink_url' => isset($notify_datas['deeplink_url']) ? $notify_datas['deeplink_url'] : "",
                    'ed' => isset($notify_datas['ed']) ? $notify_datas['ed'] : "",
                    'pd' =>  isset($notify_datas['pd']) ? $notify_datas['pd'] : "",
                    'book_num' => isset($notify_datas['book_num']) ? $notify_datas['book_num'] :  "",
                    'chapter_num' => isset($notify_datas['chapter_num']) ? $notify_datas['chapter_num'] :  "",
                    'verse_num' => isset($notify_datas['verse_num']) ? $notify_datas['verse_num'] :  "",
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

    //     $fields = array
    //     (
    //         "registration_ids" => $registrationIds,
    //         'priority' => 'high',
    //         "data" => array(
    //             'body'              => ($n_msg),
    //             'title'             => ($n_title),
    //             'image'             => ($n_image),
    //             'sound'             => 'default',
    //             'icon'              => 'icon',
    //         	'notificationType'  => ($notificationType),
    //         	'uniqID'            => ($uniqueID),
    //         	'deeplink_url'      => ($deeplink_url),
    //         	'ed'                => ($ed),
    //         	'pd'                => ($pd),
    //         	'is_remote_notify'  => ($is_remote_notify),
    //         	'booknumber'        => ($book_number),
    //         	'chapternumber'     => ($chapter_number),
    //         	'versenumber'       => ($verse_number),
    //         )
    //     );


    //     $json_encode_arr = json_encode($fields,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    //     // $json_encode_arr = json_encode($fields);
    //     //  echo ($json_encode_arr);
    //     //   exit;

    //     $headers = array(
    //         'Content-Type:application/json;charset=UTF-8',
    //         'Authorization:key='.$API_ACCESS_KEY
    //     );

    //     $ch = curl_init();
    //     curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    //     curl_setopt( $ch,CURLOPT_POST, true );
    //     curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    //     curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    //     curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    //     curl_setopt( $ch,CURLOPT_POSTFIELDS, $json_encode_arr);


    //     $result = curl_exec($ch);
    //     $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //     dd($result);
    //     $resOfpush=array();
    //     $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    //     if($result === FALSE)
    //     {
    //         $resOfpush= array("status"=>"0","curl_msg"=> "CURL Result:".$result." CURL Error:".curl_error($ch),"http_status"=>$http_status_code);
    //         // throw new Exception("Curl failed: ".curl_error($http2ch));
    //     }
    //     else
    //     {
    //         $resOfpush= array("status"=>"1","curl_msg"=> "CURL Result:".$result." CURL Error:".curl_error($ch),"http_status"=>$http_status_code);
    //     }

    //     // Close connection
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
    
        // Initialize Firebase
        $firebase = (new \Kreait\Firebase\Factory)->withServiceAccount($serviceAccountFile);
        $messaging = $firebase->createMessaging();
    
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
        //  development url, or use http://api.development.push.apple.com for development environment
        //  production url, or use http://api.push.apple.com for production environment
        if($data['app_mode_p_d'] == 1 ){
            //development
            $url = 'https://api.development.push.apple.com';
        }
        elseif($data['app_mode_p_d'] == 2){
            //production
            $url = 'https://api.push.apple.com';
        }
        // dd($data);
        $keyfile = $data['ios_key_id_p8file'];
        $keyid = $data['ios_key_id'];
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

        $http_status_code = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);
        // dd($http_status_code);
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


    //this function is to use this list to cron job contains to added
    public function getUpdatedDevList($n_id)
    {
        $res = ['res' => false];

        $notificationFilterData = DB::table('notification_filter_data')
            ->where('n_id', $n_id)
            ->first();

        if ($notificationFilterData) {
            $appDeviceId = unserialize($notificationFilterData->appDeviceId);

            if (!empty($appDeviceId)) {
                // appDeviceId is available
                return $res;
            } else {
                // appDeviceId is not available, so get filter data
                // get stored query
                $notificationDataQuery = DB::table('notification_data_query')
                    ->where('n_id', $n_id)
                    ->first();

                if ($notificationDataQuery) {
                    $sql_db = $notificationDataQuery->query;
                    $result = DB::select($sql_db);

                    if ($result) {
                        $resArr = [];
                        $temp_app_id = null;

                        foreach ($result as $row) {
                            if (is_null($temp_app_id)) {
                                $temp_app_id = $row->dev_app_id;
                            }

                            if ($row->dev_app_id != $temp_app_id) {
                                $temp_app_id = $row->dev_app_id;
                            }

                            $resArr[$temp_app_id][] = $row->dev_id;
                        }

                        $dev_ids = serialize($resArr);
                        $res['res'] = true;
                        $res['target_dev'] = $dev_ids;
                        $res['device_total_count'] = count($result);

                        return $res;
                    }
                }
            }
        }

        return $res;
    }

    private function insert_into_inprogress_all_apps($n_id, $app_ids, $device_ids)
    {
        $dev_count_total = count($device_ids);
        $modulo = 200;
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

}
