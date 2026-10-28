<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Email;
use App\Models\Dictionary;
use App\Models\Description;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use DB;
use Http;


class MailCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailCron:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'every days send mail to the user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        // try {
        //     $apps = DB::table('apps')->where('is_live_or_not',0)->get();
        //     foreach($apps as $item){
        //         $appId = $item->app_id;

        //         if($item->app_type_android == "Android"){
        //             $url = "https://play.google.com/store/apps/details?id=".$item->android_package_name;
        
        //             // Send a GET request to the URL
        //             $response = Http::timeout(10)->get($url);
        //             // Check if the URL is accessible
        //             if ($response->successful()) {
        //                 // Update the app's live status to 1
        //                 DB::table('apps')->where('app_id', $appId)->update(['is_live_or_not' => 1]);
        //             } else {
        //                 // If the response is not successful, set live to 0
        //                 DB::table('apps')->where('app_id', $appId)->update(['is_live_or_not' => 0]);
        //             }
                    
        //         }
                
        //         // Check iOS apps
        //         if ($item->app_type_ios == "IOS") {
        //             $iosUrl = "https://apps.apple.com/app/id" . $item->ios_bundle_id;
        
        //             try {
        //                 // Send a GET request to the iOS app URL
        //                 $response = Http::timeout(10)->get($iosUrl);
        
        //                 // Check if the URL is accessible
        //                 if ($response->successful()) {
        //                     DB::table('apps')->where('app_id', $appId)->update(['is_live_or_not' => 1]);
        //                 } else {
        //                     DB::table('apps')->where('app_id', $appId)->update(['is_live_or_not' => 0]);
        //                 }
        //             } catch (\Exception $e) {
        //                 DB::table('apps')->where('app_id', $appId)->update(['is_live_or_not' => 0]);
        //             }
        //         }
        //     }
        //     dd(1);
        // } catch (\Exception $e) {
        //     dd($e->getMessage());
        //     // Handle errors (e.g., URL not reachable)
        //     DB::table('apps')->where('app_id', $appId)->update(['is_live_or_not' => 0]);
        //     // return "App is not live. Error: " . $e->getMessage();
        // }
        
        
        dd(2);
        // Retrieve all records from table1 with app_version 2, limited to 100
        $table1Records = DB::connection('mysql_fifth')
            ->table('z_dynamic_227_1677130807')
            ->where('dev_id','>',8527)
            ->limit(3500)
            ->pluck('appDeviceId')
            ->toArray();
            
        // Initialize the query
        $query = DB::table('z_dynamic_227_1677130807 as table1');
        
        // Apply LIKE condition for each appDeviceId in table1Records
        $query->where(function ($query) use ($table1Records) {
            foreach ($table1Records as $record) {
                $query->orWhere('table1.appDeviceId', 'LIKE', "%{$record}%");
            }
        });
        
        // Retrieve user_ids
        $userIds = $query->pluck('table1.user_id')->toArray();
        
        dd($userIds);


        dd($userIds);
    }
}
