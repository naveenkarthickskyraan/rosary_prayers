<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Mail;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\App;
use App\Models\NotificationData;
use App\Models\NotificationSendData;
use App\Models\NotificationSendingData;
use App\Models\iosTeamId;
use App\Models\iosKeyId;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;

class InsertCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'InsertCron:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Description values are updated into null';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
        \Log::info('InsertCron Started');

        // Existing handle() code
        $notifications = DB::table('notification_sending_data')
                            ->where('status', 'In Progress')
                            ->orderBy('id')
                            ->limit(1)
                            ->get();

        foreach ($notifications as $notification) {
                // dd($notification);

            $n_sending_id = $notification->id;
            $n_id = $notification->n_id;

            $app_ids = unserialize($notification->app_id);

            $device_ids = unserialize($notification->device_ids);
//             dd(
//     count($app_ids),
//     count($device_ids)
// );

            $notify_datas = NotificationData::getNotificationData($n_id);
            // dd($notify_datas);

            $notify_ed = [
                'ed' => '',
                'pd' => ''
            ];

            $notify_datas = array_merge($notify_datas, $notify_ed);
            // dd(gettype($notify_datas), $notify_datas);

            $app_mode_p_d = $notify_datas['app_mode_p_d'];

            $cur_Date = Carbon::now();

            DB::table('notification_sending_data')
                ->where('id', $n_sending_id)
                ->update(['start_at' => $cur_Date]);


            $send_app_array = [];
            $total_device_count = 0;

            $count_array = [
                'total_app_count' => count($send_app_array),
                'total_device_count' => 0,
                'total_device_count_suc' => 0,
                'total_device_count_fail' => 0
            ];
// dd(
//     count($app_ids),
//     count($device_ids),
//     $app_mode_p_d
// );
            if (count($app_ids) == 1) {
                foreach ($app_ids as $app_id) {
                    // dd($app_ids, count($app_ids), $app_mode_p_d);
                    $device_datas = $this->getDeviceIdsOne($device_ids, $app_id, $app_mode_p_d);
                    $total_device_count += count($device_datas);
                    $count_array['total_device_count'] = $total_device_count;

                    $count_array = $this->setupPushNotifyOne($notify_datas, $device_datas, $count_array, $app_mode_p_d);
                    // dd($count_array);
                }
            } else {
                $device_datas = $this->getDeviceIdsAllApps($device_ids, $app_ids, $app_mode_p_d);
    //              dd(
    //     count($device_datas),
    //     array_slice($device_datas, 0, 2)
    // );
                $total_device_count += count($device_datas);
                $count_array['total_device_count'] = $total_device_count;

                $count_array = $this->setupPushNotifyOne($notify_datas, $device_datas, $count_array, $app_mode_p_d);

            }

            $n_status = 'Completed';
            $n_msg = $count_array['total_device_count_suc'];
            $cur_Date = Carbon::now();

            DB::table('notification_sending_data')
                ->where('id', $n_sending_id)
                ->update([
                    'status' => $n_status,
                    'count_suc' => $n_msg,
                    'updated_at' => $cur_Date
                ]);

            $this->UpdateOneNIDcompleted($n_id);
        }

    } catch (\Throwable $e) {

        \Log::error('InsertCron Error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        dd($e->getMessage(), $e->getFile(), $e->getLine());
    }
        //Step 1 to get sending data wise cron data to get from the table of notification_sending_data

        
    }

    //Get All Device using for loop to get all apps and all device list
    function getDeviceIdsAllApps($device_ids, $app_ids, $app_mode_p_d)
    {
        $resDeviceids = [];

        if ($app_mode_p_d == 1) {
            //development
            $app_mode_p_d = 1;
        } elseif ($app_mode_p_d == 2) {
            //production
            $app_mode_p_d = 0;
        }

        $app_ids = array_unique($app_ids);

        foreach ($app_ids as $app_id) {
            $app = App::where('app_id', $app_id)->first();

            if ($app) {
                $app_table_name = $app->app_table_name;
                $ids = implode("','", $device_ids);

                $devices = DB::table($app_table_name . ' as d')
                    ->join('apps as a', 'd.dev_app_id', '=', 'a.app_id')
                    ->whereIn('d.dev_id', $device_ids)
                    ->where('d.appMode', $app_mode_p_d)
                    ->where('d.dev_app_id', $app_id)
                    ->select(
                        'd.dev_id',
                        'd.dev_type',
                        'd.dev_gcm_id',
                        'd.appMode as appMode_p_d',
                        'a.app_id',
                        'a.android_api_key',
                        'a.app_type_android',
                        'a.app_type_ios',
                        'a.ios_key_id',
                        'a.ios_team_id',
                        'a.ios_bundle_id',
                        'a.notify_file_json'
                    )
                    ->get();


                foreach ($devices as $device) {
                    $resDeviceids[] = [
                        'dev_id' => $device->dev_id,
                        'device_gcm_id' => $device->dev_gcm_id,
                        'dev_type' => $device->dev_type,
                        'appMode_p_d' => $device->appMode_p_d,
                        'app_id' => $device->app_id,
                        'android_api_key' => $device->android_api_key,
                        'app_type_android' => $device->app_type_android,
                        'app_type_ios' => $device->app_type_ios,
                        'ios_team_id' => !empty($device->ios_team_id) ? $this->getTeamId($device->ios_team_id) : '0',
                        'ios_key_id' => !empty($device->ios_key_id) ? $this->getKeyId($device->ios_key_id)[0] : '0',
                        'ios_key_id_p8file' => !empty($device->ios_key_id) ? $this->getKeyId($device->ios_key_id)[1] : '0',
                        'ios_bundle_id' => $device->ios_bundle_id,
                        'notify_file_json' => $device->notify_file_json,
                    ];
                }
            }
        }

        return $resDeviceids;
    }

    function getDeviceIdsOne($device_ids, $app_id, $app_mode_p_d)
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
                        'apps.ios_bundle_id',
                        'apps.notify_file_json'
                    )
                    ->get();

                foreach ($devices as $device) {
                    $resDeviceids[] = [
                        'dev_id' => $device->dev_id,
                        'device_gcm_id' => $device->dev_gcm_id,
                        'dev_type' => $device->dev_type,
                        'appMode_p_d' => $device->appMode_p_d,
                        'app_id' => $device->app_id,
                        'android_api_key' => $device->android_api_key,
                        'app_type_android' => $device->app_type_android,
                        'app_type_ios' => $device->app_type_ios,
                        'ios_team_id' => !empty($device->ios_team_id) ? $this->getTeamId($device->ios_team_id) : '0',
                        'ios_key_id' => !empty($device->ios_key_id) ? $this->getKeyId($device->ios_key_id)[0] : '0',
                        'ios_key_id_p8file' => !empty($device->ios_key_id) ? $this->getKeyId($device->ios_key_id)[1] : '0',
                        'ios_bundle_id' => $device->ios_bundle_id,
                        'notify_file_json' => $device->notify_file_json,
                    ];
                }
            }
        }

        return $resDeviceids;
    }

    function setupPushNotifyOne($notify_datas, $device_datas, $count_array, $app_mode_p_d)
    {
    // dd("Entered setupPushNotifyOne", count($device_datas));
        $dev_count = 0;
        $app_id = 0;
        foreach ($device_datas as $device_data) {
            // dd($device_datas[0]);
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
// dd($data);
                $noti_msg = $this->sendpushnotify_android($data);

                $send_status = $noti_msg['http_status'] == 200 ? 'Completed' : 'Not Completed';
                if ($noti_msg['http_status'] != 200) {
                    NotificationSendData::create([
                        'n_id' => $notify_Id,
                        'app_id' => $app_id,
                        'dev_id' => $device_data['dev_id'],
                        'dev_type' => 1,
                        'app_mode_p_d' => $app_mode_p_d,
                        'status' => $send_status,
                        'curl_msg' => $noti_msg['curl_msg'],
                        'http_status_code' => $noti_msg['http_status']
                    ]);
                }

                $dev_count += $noti_msg['http_status'] == 200 ? 1 : 0;
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
    // //     return [
    // //     'status' => '1',
    // //     'curl_msg' => 'Temporarily disabled',
    // //     'http_status' => 200
    // // ];

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
    //     // dd($result);
    //     Log::info([
    //         'http_status' => $http_status_code,
    //         'response' => $result
    //     ]);
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
        try {
    
            $notificationType = (string) $data['notificationType'];
            $uniqueID         = (string) $data['uniqueID'];
            $deeplink_url     = (string) $data['deeplink_url'];
            $ed               = (string) $data['ed'];
            $pd               = (string) $data['pd'];
    
            $book_number    = (string) $data['book_num'];
            $chapter_number = (string) $data['chapter_num'];
            $verse_number   = (string) $data['verse_num'];
    
            $n_title = html_entity_decode($data['n_title']);
            $n_msg   = html_entity_decode($data['n_text']);
            $n_image = $data['n_image'];
    
            $deviceToken = $data['registrationIds'];
    
            // service account json
            $serviceAccount = public_path($data['notify_file_json']);
    
            if (!file_exists($serviceAccount)) {
                return [
                    'status' => '0',
                    'curl_msg' => 'Firebase JSON file not found : '.$serviceAccount,
                    'http_status' => 500
                ];
            }
    
            $messaging = (new Factory)
                ->withServiceAccount($serviceAccount)
                ->createMessaging();
    
            $message = CloudMessage::withTarget('token', $deviceToken)
                ->withNotification(
                    Notification::create($n_title, $n_msg, $n_image ?: null)
                )
                ->withData([
                    'body' => $n_msg,
                    'title' => $n_title,
                    'image' => $n_image,
                    'sound' => 'default',
                    'icon' => 'icon',
                    'notificationType' => $notificationType,
                    'uniqID' => $uniqueID,
                    'deeplink_url' => $deeplink_url,
                    'ed' => $ed,
                    'pd' => $pd,
                    'is_remote_notify' => '1',
                    'booknumber' => $book_number,
                    'chapternumber' => $chapter_number,
                    'versenumber' => $verse_number,
                ])
                ->withAndroidConfig(
                    AndroidConfig::fromArray([
                        'priority' => 'high',
                    ])
                );
    
            $messaging->send($message);
    
            return [
                'status' => '1',
                'curl_msg' => 'Notification Sent Successfully',
                'http_status' => 200
            ];
    
        } catch (\Throwable $e) {
    
            Log::error('Android Push Error', [
                'message' => $e->getMessage(),
                'token'   => $data['registrationIds']
            ]);
    
            return [
                'status' => '0',
                'curl_msg' => $e->getMessage(),
                'http_status' => 500
            ];
        }
    }

    private function sendpushnotify_iOS($data)
    {
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


        // Encode it as json.
        $message = json_encode($payloadArray);
        // $message = json_encode($payloadArray,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);

        // $message = (utf8_decode(($message)));
        // echo $message;
        // exit;

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

    function UpdateOneNIDcompleted($n_id)
    {
        $totalDeviceCount = NotificationSendingData::where('n_id', $n_id)->count();

        $completedDeviceCount = NotificationSendingData::where('n_id', $n_id)
            ->where('status', 'Completed')
            ->count();

        if ($totalDeviceCount > 0 && $totalDeviceCount == $completedDeviceCount) {
            $totalDeviceCountSucc = NotificationSendingData::where('n_id', $n_id)
                ->where('status', 'Completed')
                ->sum('count_suc');

            $totalDeviceCountSucc = $totalDeviceCountSucc ?: 0;

            $nStatus = 'Completed (' . $totalDeviceCountSucc . ' / ' . $totalDeviceCount . ')';
            $nMsg = 'All devices sending success';

            // Update status in main table (notification_data)
            DB::table('notification_data')->where('n_id', $n_id)
                ->update([
                    'n_status' => $nStatus,
                    'n_msg' => $nMsg,
                    'n_updated_at' => now() // Adjust this based on your timezone settings
                ]);

            // You may also choose to delete the related entries from notification_sending_data
            // NotificationSendingData::where('n_id', $n_id)->delete();
        }

        return true;
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


}
