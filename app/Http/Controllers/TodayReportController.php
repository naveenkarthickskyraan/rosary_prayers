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
use Carbon\Carbon;

use Schema;

class TodayReportController extends Controller
{
    public function index(Request $request)
    {
        // Default values if not an AJAX request
        $TotalRowCount = 0;
        $TotalUsersTodayCount = 0;
        $TotalActiveUsersTodayCount = 0;
        $totalApps = 0;
        $todayAppCreated = 0;
    
        return view('admin.report', compact('todayAppCreated', 'TotalRowCount', 'TotalUsersTodayCount', 'totalApps','TotalActiveUsersTodayCount'));
    }
    
    public function today_count(Request $request)
    {
        if ($request->ajax()) {
            $todayDate = \Carbon\Carbon::today()->subDay();
        
            // Fetch all apps once
            $TotalAppList = App::where('app_deleted', 0)
                ->get(['app_table_name', 'app_type_android', 'app_type_ios']);
        
            // Initialize counters
            $TotalRowCount = 0;
            $TotalUsersTodayCount = 0;
            $androidRowCount = 0;
            $iosRowCount = 0;
            $TotalActiveUsersTodayCount = 0;
            $androidActiveRowCount = 0;
            $iosActiveRowCount = 0;
            
            // Fetch counts in a single pass for each app table
            foreach ($TotalAppList as $app) {
                // Count devices and those added today for each app's table
                $result = DB::table($app->app_table_name)
                    ->selectRaw('COUNT(dev_id) as total_devices, 
                                 SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) AS today_added_count_activity,
                                 SUM(CASE WHEN DATE(activity_time) = ? THEN 1 ELSE 0 END) AS today_added_count_created', 
                                 [$todayDate, $todayDate])
                    ->first();
        
                // Aggregate results
                $TotalRowCount += $result->total_devices ?? 0;
                $TotalUsersTodayCount += $result->today_added_count_activity ?? 0;
                $TotalActiveUsersTodayCount += $result->today_added_count_created ?? 0;
        
                if ($app->app_type_android == 'Android') {
                    $androidRowCount += $result->today_added_count_activity ?? 0;
                    $androidActiveRowCount += $result->today_added_count_created ?? 0;
                } else {
                    $iosRowCount += $result->today_added_count_activity ?? 0;
                    $iosActiveRowCount += $result->today_added_count_created ?? 0;
                }
            }
        
            // Total number of apps and apps created today
            $totalApps = $TotalAppList->count();
            $todayAppCreated = $TotalAppList->where('created_at', '>=', $todayDate)->count();
        
            return response()->json([
                'app_list' => $todayAppCreated,
                'totalRowCount' => $TotalRowCount,
                'androidRowCount' => $androidRowCount,
                'androidActiveRowCount' => $androidActiveRowCount,
                'iosRowCount' => $iosRowCount,
                'iosActiveRowCount' => $iosActiveRowCount,
                'admin' => $TotalUsersTodayCount,
                'TotalActiveUsersTodayCount' => $TotalActiveUsersTodayCount,
                'all' => $totalApps
            ]);
        }
    }


    
    // public function datatable_index(Request $request)
    // {
    //     $days = 7;
    //     $today = \Carbon\Carbon::today()->subDay();;
    //     $lastWeek = $today->copy()->subDays($days);
    
    //     // Get the list of apps
    //     $categories = DB::table('apps')
    //         ->where('app_deleted', 0)
    //         ->where('is_live_or_not', 1)
    //         ->get();
    
    //     // Calculate downloads data for each app
    //     $categories = $categories->map(function ($app) use ($today, $lastWeek) {
    //         // Check if the app's table exists to avoid runtime errors
    //         if (!Schema::hasTable($app->app_table_name)) {
    //             $app->todays_downloads = 0;
    //             $app->total_downloads_last_7_days = 0;
    //             return $app;
    //         }
    
    //         // Today's downloads
    //         $todaysDownloads = DB::table($app->app_table_name)
    //             ->whereDate('created_at', $today)
    //             ->count('dev_id');
                
    //         // Downloads from the last 7 days
    //         $totalDownloadsLast7Days = DB::table($app->app_table_name)
    //             ->whereBetween('created_at', [$lastWeek, $today])
    //             ->count('dev_id');
                
    //         $averageDownloadsLast7Days = round($totalDownloadsLast7Days / 7, 2) ;
            
                
    //         // Calculate the ratio
    //         $ratio = $averageDownloadsLast7Days > 0 
    //             ? $todaysDownloads / $averageDownloadsLast7Days 
    //             : 0; // Avoid division by zero
    
    //         // Determine increase, decrease, or no change
    //         $status = $ratio > 1 ? 'increased' : ($ratio < 1 ? 'decreased' : 'no change');
            
    //         // Attach computed values to the app instance
    //         $app->ratio = $status;
    //         $app->todays_downloads = (int) $todaysDownloads;
    //         $app->total_downloads_last_7_days = (int) $totalDownloadsLast7Days;
    
    //         return $app;
    //     });
    
    //     // Sort by today's downloads in descending order and limit to 50 records
    //     $categories = $categories->sortByDesc('todays_downloads')->values();
    
    //     // Return data to DataTables
    //     return DataTables::of($categories)
    //         ->editColumn('created_at', function($app) {
    //             return date('d-m-Y', strtotime($app->created_at));
    //         })
    //         ->addColumn('app_type_android', function($app) {
    //             return empty($app->android_package_name) ? $app->ios_bundle_id : $app->android_package_name;
    //         })
    //         ->addColumn('average_downloads_last_7_days', function($app) {
    //             $average = round($app->total_downloads_last_7_days / 7);
    //             return "Total: {$app->total_downloads_last_7_days},<br>Average (last 7 days): {$average}";
    //         })
    //         ->addColumn('todays_downloads', function($app) {
    //             return $app->todays_downloads;
    //         })
    //         ->rawColumns(['average_downloads_last_7_days', 'action', 'status', 'app_type_android'])
    //         ->make(true);
    // }
    
    public function datatable_index(Request $request)
    {
        $days = 7;
        $today = \Carbon\Carbon::today()->subDay();
        $lastWeek = $today->copy()->subDays($days);
    
        // Get the list of apps
        $apps = DB::table('apps')
            ->where('app_deleted', 0)
            ->where('is_live_or_not', 1)
            ->get();
    
        // Prepare an array to hold app download counts
        $downloadsData = [];
    
        // Collect app table names
        $appTableNames = $apps->pluck('app_table_name')->toArray();
    
        // Fetch today's downloads and last 7 days downloads in a single query
        foreach ($appTableNames as $tableName) {
            if (Schema::hasTable($tableName)) {
                $todaysDownloads = DB::table($tableName)
                    ->whereDate('created_at', $today)
                    ->count('dev_id');
    
                $totalDownloadsLast7Days = DB::table($tableName)
                    ->whereBetween('created_at', [$lastWeek, $today])
                    ->count('dev_id');
    
                $downloadsData[$tableName] = [
                    'todays_downloads' => $todaysDownloads,
                    'total_downloads_last_7_days' => $totalDownloadsLast7Days,
                ];
            }
        }
    
        // Calculate additional fields for each app
        $categories = $apps->map(function ($app) use ($downloadsData, $days) {
            $todaysDownloads = $downloadsData[$app->app_table_name]['todays_downloads'] ?? 0;
            $totalDownloadsLast7Days = $downloadsData[$app->app_table_name]['total_downloads_last_7_days'] ?? 0;

            // Attach computed values to the app instance
            $app->todays_downloads = (int)$todaysDownloads;
            $app->total_downloads_last_7_days = (int)$totalDownloadsLast7Days;
    
            return $app;
        });
    
        // Sort by today's downloads in descending order and limit to 50 records
        $categories = $categories->sortByDesc('todays_downloads')->values()->take(50);
    
        // Return data to DataTables
        return DataTables::of($categories)
            ->editColumn('created_at', function ($app) {
                return date('d-m-Y', strtotime($app->created_at));
            })
            ->addColumn('app_type_android', function ($app) {
                return empty($app->android_package_name) ? $app->ios_bundle_id : $app->android_package_name;
            })
            ->addColumn('average_downloads_last_7_days', function ($app) {
                $average = round($app->total_downloads_last_7_days / 7);
                return "Total: {$app->total_downloads_last_7_days},<br>Average (last 7 days): {$average}";
            })
            ->addColumn('todays_downloads', function ($app) {
                return $app->todays_downloads;
            })
            ->rawColumns(['average_downloads_last_7_days', 'action', 'status', 'app_type_android'])
            ->make(true);
    }
    
    // public function high_downgrade(Request $request)
    // {
    //     $days = 7;
    //     $today = \Carbon\Carbon::today()->subDay();
    //     $lastWeek = $today->copy()->subDays($days);
    //     $today = \Carbon\Carbon::today()->subDay();
    //     $startDate = $today->copy()->subDays($days); // Start from 7 days ago (first day)
    //     $endDate = $today->copy();
        
    //     // Get the list of apps
    //     $categories = DB::table('apps')
    //         ->where('app_deleted', 0)
    //         ->where('is_live_or_not', 1)
    //         ->get();
    
    //     // Calculate downloads data for each app
    //     $categories = $categories->map(function ($app) use ($today, $lastWeek, $days, $endDate, $startDate) {
    //         // Check if the app's table exists to avoid runtime errors
    //         if (!Schema::hasTable($app->app_table_name)) {
    //             $app->downloads = array_fill(0, 7, ['count' => 0, 'trend' => '']);
    //             $app->todays_downloads = 0;
    //             $app->total_downloads_last_7_days = 0;
    //             $app->downloads = array_fill(0, $days, 0); // Initialize 7-day array with zeroes
    //             $app->ratio = 0;
    //             return $app;
    //         }
    
    //         // Collect downloads for each of the last 7 days (from 7 days ago to yesterday) with trend arrows
    //         $downloads = [];
    //         $previousCount = null;
    //         $totalCount = 0;
            
    //         for ($i = 1; $i <= 7; $i++) {
    //             $date = $startDate->copy()->addDays($i);
    //             $count = DB::table($app->app_table_name)
    //                 ->whereDate('created_at', $date)
    //                 ->count('dev_id');
    
    //             // Determine trend by comparing each day to the previous day
    //             if ($previousCount !== null) {
    //                 $trend = $count > $previousCount ? 'up' : ($count < $previousCount ? 'down' : 'same');
    //             } else {
    //                 $trend = 'same'; // No trend for the first day in the series
    //             }
                
    //             $downloads[$date->format('d-m-Y')] = [
    //                 'count' => $count,
    //                 'trend' => $trend,
    //             ];
    //             $totalCount += $count;
    //             $previousCount = $count; // Update previous count for the next iteration
    //         }
    
    //         // Calculate 7-day total and average
    //         $app->downloads = $downloads; // Reverse to start with oldest day
            
    //         // Calculate total downloads over the last 7 days
    //         $totalDownloadsLast7Days = DB::table($app->app_table_name)
    //             ->whereBetween('created_at', [$lastWeek, $today])
    //             ->count('dev_id');
            
    //         // Calculate today’s downloads
    //         $todaysDownloads = DB::table($app->app_table_name)
    //             ->whereDate('created_at', $today)
    //             ->count('dev_id');
            
    //         // Calculate the 7-day average
    //         $averageDownloadsLast7Days = $totalDownloadsLast7Days > 0 ? round($totalDownloadsLast7Days / 7, 2) : 0;
            
    //         // Calculate the percentage increase or decrease
    //         if ($averageDownloadsLast7Days > 0) {
    //             $percentageChange = round((($todaysDownloads - $averageDownloadsLast7Days) / $averageDownloadsLast7Days) * 100, 2);
                
    //             // Determine increase or decrease and format the result
    //             if ($percentageChange > 0) {
    //                 $app->ratio = abs($percentageChange);
    //             } elseif ($percentageChange < 0) {
    //                 $app->ratio = abs($percentageChange);
    //             } else {
    //                 $app->ratio = 0;
    //             }
    //         } else {
    //             $app->ratio = 'N/A'; // When there is no data for the last 7 days
    //         }
            
    //         $app->percentageChange = $percentageChange;

    //         // Format the ratio as a percentage with increase/decrease indication
    //         // $app->ratio = abs($percentageChange) . '%';
    
    //         // Attach computed values to the app instance
    //         $app->todays_downloads = (int) $todaysDownloads;
    //         $app->total_downloads_last_7_days = (int) $totalDownloadsLast7Days;
    //         $app->average_downloads_last_7_days = $averageDownloadsLast7Days;
    //         return $app;
    //     })->where('percentageChange',"<=", 0)->sortByDesc('ratio')->values(); // Sort by ratio in ascending order to show highest decrease first
    
    //     // Return data to DataTables
    //     return DataTables::of($categories)
    //         ->editColumn('created_at', function($app) {
    //             return date('d-m-Y', strtotime($app->created_at));
    //         })
    //         ->addColumn('app_type_android', function($app) {
    //             return empty($app->android_package_name) ? $app->ios_bundle_id : $app->android_package_name;
    //         })
    //         ->addColumn('average_downloads_last_7_days', function($app) {
    //             $average = round($app->total_downloads_last_7_days / 7);
    //             return "Total: {$app->total_downloads_last_7_days},<br>Average (last 7 days): {$average}";
    //         })
    //         ->addColumn('todays_downloads', function($app) {
    //             return $app->todays_downloads;
    //         })
    //         ->addColumn('ratio', function($app) {
    //             return $app->percentageChange > 1 ? $app->ratio ." <i class='fa fa-solid fa-arrow-up text-success fa-lg'></i>" :  $app->ratio ." <i class='fa fa-solid fa-arrow-down text-danger fa-lg'></i>" ; // Format ratio to one decimal place
    //         })
    //         ->addColumn('daily_downloads', function($app) {
    //             $trendHtml = '<div class="space-c">';
    //             foreach ($app->downloads as $date => $data) {
    //                 $arrow = $data['trend'] === 'up' ? "<i class='fa fa-solid fa-arrow-up text-success '></i>" : ($data['trend'] === 'down' ? "<i class='fa fa-solid fa-arrow-down text-danger '></i>" : '<i class="fa fa-solid fa-arrow-right text-primary"></i>');
    //                 $color = $data['trend'] === 'up' ? 'green' : ($data['trend'] === 'down' ? 'red' : 'black');
    //                 $trendHtml .= "<span style='color: {$color}'>{$date}: {$data['count']} {$arrow}</span><br>";
    //             }
    //             $trendHtml .="</div>";
                
    //             return $trendHtml;
    //         })
    //         ->rawColumns(['ratio','average_downloads_last_7_days', 'daily_downloads', 'app_type_android'])
    //         ->make(true);
    // } 
    
    public function high_downgrade(Request $request)
    {
        $days = 7;
        $today = \Carbon\Carbon::today()->subDay();
        $lastWeek = $today->copy()->subDays($days);
        $startDate = $today->copy()->subDays($days);
        
        // Get the list of apps
        $categories = DB::table('apps')
            ->where('app_deleted', 0)
            ->where('is_live_or_not', 1)
            ->get();
    
        // Calculate downloads data for each app
        $categories = $categories->map(function ($app) use ($today, $lastWeek, $days, $startDate) {
            // Check if the app's table exists to avoid runtime errors
            if (!Schema::hasTable($app->app_table_name)) {
                $app->downloads = array_fill(0, 7, ['count' => 0, 'trend' => '', 'percentage_change' => 0]);
                $app->todays_downloads = 0;
                $app->total_downloads_last_7_days = 0;
                $app->ratio = 0;
                return $app;
            }
    
            // Collect downloads for each of the last 7 days (from 7 days ago to yesterday)
            $downloads = [];
            $previousCount = null;
            $totalCount = 0;
    
            for ($i = 1; $i < 7; $i++) {
                $date = $startDate->copy()->addDays($i);
                $count = DB::table($app->app_table_name)
                    ->whereDate('created_at', $date)
                    ->count('dev_id');
    
                // Determine trend and percentage change
                $trend = 'same';
                $percentageChange = 0;
    
                if ($previousCount !== null) {
                    $trend = $count > $previousCount ? 'up' : ($count < $previousCount ? 'down' : 'same');
                    if ($previousCount > 0) {
                        $percentageChange = round((($count - $previousCount) / $previousCount) * 100, 2);
                    }
                }
    
                $downloads[$date->format('d-m-Y')] = [
                    'count' => $count,
                    'trend' => $trend,
                    'percentage_change' => $percentageChange,
                ];
                $totalCount += $count;
                $previousCount = $count; // Update previous count for the next iteration
            }
    
            // Calculate total downloads over the last 7 days
            $totalDownloadsLast7Days = $totalCount;
    
            // Calculate today’s downloads
            $todaysDownloads = DB::table($app->app_table_name)
                ->whereDate('created_at', $today)
                ->count('dev_id');
    
            // Calculate the 7-day average
            $averageDownloadsLast7Days = $totalDownloadsLast7Days > 0 ? round($totalDownloadsLast7Days / 7, 2) : 0;
    
            // Calculate the percentage increase or decrease
            if ($averageDownloadsLast7Days > 0) {
                $percentageChange = round((($todaysDownloads - $averageDownloadsLast7Days) / $averageDownloadsLast7Days) * 100, 2);
                $app->ratio = abs($percentageChange);
            } else {
                $app->ratio = 'N/A'; // When there is no data for the last 7 days
            }
    
            $app->percentageChange = $percentageChange;
    
            // Attach computed values to the app instance
            $app->todays_downloads = (int) $todaysDownloads;
            $app->total_downloads_last_7_days = (int) $totalDownloadsLast7Days;
            $app->average_downloads_last_7_days = $averageDownloadsLast7Days;
            $app->downloads = $downloads; // Store downloads with trends and percentage changes
    
            return $app;
        })->where('percentageChange', "<=", 0)->sortByDesc('ratio')->values(); // Sort by ratio in ascending order to show highest decrease first
    
        // Return data to DataTables
        return DataTables::of($categories)
            ->editColumn('created_at', function($app) {
                return date('d-m-Y', strtotime($app->created_at));
            })
            ->addColumn('app_type_android', function($app) {
                return empty($app->android_package_name) ? $app->ios_bundle_id : $app->android_package_name;
            })
            ->addColumn('average_downloads_last_7_days', function($app) {
                $average = round($app->total_downloads_last_7_days / 7);
                return "Total: {$app->total_downloads_last_7_days},<br>Average (last 7 days): {$average}";
            })
            ->addColumn('todays_downloads', function($app) {
                return $app->todays_downloads;
            })
            ->addColumn('ratio', function($app) {
                return $app->percentageChange > 1 ? 
                    $app->ratio ."% <i class='fa fa-solid fa-arrow-up text-success fa-lg'></i>" :  
                    $app->ratio ."% <i class='fa fa-solid fa-arrow-down text-danger fa-lg'></i>";
            })
            ->addColumn('daily_downloads', function($app) {
                $trendHtml = '<div class="space-c">';
                foreach ($app->downloads as $date => $data) {
                    $arrow = $data['trend'] === 'up' ? "<i class='fa fa-solid fa-arrow-up text-success'></i>" : 
                            ($data['trend'] === 'down' ? "<i class='fa fa-solid fa-arrow-down text-danger'></i>" : 
                            '<i class="fa fa-solid fa-arrow-right text-primary"></i>');
                    $color = $data['trend'] === 'up' ? 'green' : ($data['trend'] === 'down' ? 'red' : 'black');
                    $percentageText = $data['percentage_change'] != 0 ? "({$data['percentage_change']}%)" : '';
                    $trendHtml .= "<span style='color: {$color}'>{$date}: {$data['count']} {$arrow} {$percentageText}</span><br>";
                }
                $trendHtml .= "</div>";
                
                return $trendHtml;
            })
            ->rawColumns(['ratio', 'average_downloads_last_7_days', 'daily_downloads', 'app_type_android'])
            ->make(true);
    }

    public function appYesTodTable(Request $request)
    {
        $days = 2;
        $today = \Carbon\Carbon::today()->subDay();
        $lastWeek = $today->copy()->subDays($days);
        $today = \Carbon\Carbon::today()->subDay();
        $startDate = $today->copy()->subDays($days); // Start from 7 days ago (first day)
        $endDate = $today->copy();
        
        // Get the list of apps
        $categories = DB::table('apps')
            ->where('app_deleted', 0)
            ->where('is_live_or_not', 1)
            ->get();
    
        // Calculate downloads data for each app
        $categories = $categories->map(function ($app) use ($today, $lastWeek, $days, $endDate, $startDate) {
            // Check if the app's table exists to avoid runtime errors
            if (!Schema::hasTable($app->app_table_name)) {
                $app->downloads = array_fill(0, 7, ['count' => 0, 'trend' => '', 'percentage_change' => 0]);
                $app->todays_downloads = 0;
                $app->total_downloads_last_7_days = 0;
                $app->ratio = 0;
                return $app;
            }
    
            // Collect downloads for each of the last 7 days (from 7 days ago to yesterday)
            $downloads = [];
            $previousCount = null;
            $totalCount = 0;
    
            for ($i = 0; $i < 2; $i++) {
                $date = $startDate->copy()->addDays($i);
                $count = DB::table($app->app_table_name)
                    ->whereDate('created_at', $date)
                    ->count('dev_id');
    
                // Determine trend and percentage change
                $trend = 'same';
                $percentageChange = 0;
    
                if ($previousCount !== null) {
                    $trend = $count > $previousCount ? 'up' : ($count < $previousCount ? 'down' : 'same');
                    if ($previousCount > 0) {
                        $percentageChange = round((($count - $previousCount) / $previousCount) * 100, 2);
                    }
                }
    
                $downloads[$date->format('d-m-Y')] = [
                    'count' => $count,
                    'trend' => $trend,
                    'percentage_change' => $percentageChange,
                ];
                $totalCount += $count;
                $previousCount = $count; // Update previous count for the next iteration
            }
    
            // Calculate total downloads over the last 7 days
            $totalDownloadsLast7Days = $totalCount;
    
            // Calculate today’s downloads
            $todaysDownloads = DB::table($app->app_table_name)
                ->whereDate('created_at', $today)
                ->count('dev_id');
    
            // Calculate the 7-day average
            $averageDownloadsLast7Days = $totalDownloadsLast7Days > 0 ? round($totalDownloadsLast7Days / 2, 2) : 0;
    
            // Calculate the percentage increase or decrease
            if ($averageDownloadsLast7Days > 0) {
                $percentageChange = round((($todaysDownloads - $averageDownloadsLast7Days) / $averageDownloadsLast7Days) * 100, 2);
                $app->ratio = abs($percentageChange);
            } else {
                $app->ratio = 'N/A'; // When there is no data for the last 7 days
            }
    
            $app->percentageChange = $percentageChange;
    
            // Attach computed values to the app instance
            $app->todays_downloads = (int) $todaysDownloads;
            $app->total_downloads_last_7_days = (int) $totalDownloadsLast7Days;
            $app->average_downloads_last_7_days = $averageDownloadsLast7Days;
            $app->downloads = $downloads; // Store downloads with trends and percentage changes
    
            return $app;
        })->sortByDesc('ratio')->values(); // Sort by ratio in ascending order to show highest decrease first
    
        // Return data to DataTables
        return DataTables::of($categories)
            ->editColumn('created_at', function($app) {
                return date('d-m-Y', strtotime($app->created_at));
            })
            ->addColumn('app_type_android', function($app) {
                return empty($app->android_package_name) ? $app->ios_bundle_id : $app->android_package_name;
            })
            ->addColumn('average_downloads_last_7_days', function($app) {
                $average = round($app->total_downloads_last_7_days / 2);
                return "Total: {$app->total_downloads_last_7_days},<br>Average (last 2 days): {$average}";
            })
            ->addColumn('todays_downloads', function($app) {
                return $app->todays_downloads;
            })
            ->addColumn('ratio', function($app) {
                return $app->percentageChange > 1 ? 
                    $app->ratio ."% <i class='fa fa-solid fa-arrow-up text-success fa-lg'></i>" :  
                    $app->ratio ."% <i class='fa fa-solid fa-arrow-down text-danger fa-lg'></i>";
            })
            ->addColumn('daily_downloads', function($app) {
                $trendHtml = '<div class="space-c">';
                foreach ($app->downloads as $date => $data) {
                    $arrow = $data['trend'] === 'up' ? "<i class='fa fa-solid fa-arrow-up text-success'></i>" : 
                            ($data['trend'] === 'down' ? "<i class='fa fa-solid fa-arrow-down text-danger'></i>" : 
                            '<i class="fa fa-solid fa-arrow-right text-primary"></i>');
                    $color = $data['trend'] === 'up' ? 'green' : ($data['trend'] === 'down' ? 'red' : 'black');
                    $percentageText = $data['percentage_change'] != 0 ? "({$data['percentage_change']}%)" : '';
                    $trendHtml .= "<span style='color: {$color}'>{$date}: {$data['count']} {$arrow} {$percentageText}</span><br>";
                }
                $trendHtml .= "</div>";
                
                return $trendHtml;
            })
            ->rawColumns(['ratio', 'average_downloads_last_7_days', 'daily_downloads', 'app_type_android'])
            ->make(true);
    } 

    public function apphigUpgradeTable(Request $request)
    {
        $days = 7;
        $today = \Carbon\Carbon::today()->subDay();
        $lastWeek = $today->copy()->subDays($days);
        $today = \Carbon\Carbon::today()->subDay();
        $startDate = $today->copy()->subDays($days); // Start from 7 days ago (first day)
        $endDate = $today->copy();
        
        // Get the list of apps
        $categories = DB::table('apps')
            ->where('app_deleted', 0)
            ->where('is_live_or_not', 1)
            ->get();
    
        // Calculate downloads data for each app
        $categories = $categories->map(function ($app) use ($today, $lastWeek, $days, $endDate, $startDate) {
            // Check if the app's table exists to avoid runtime errors
            if (!Schema::hasTable($app->app_table_name)) {
                $app->downloads = array_fill(0, 7, ['count' => 0, 'trend' => '', 'percentage_change' => 0]);
                $app->todays_downloads = 0;
                $app->total_downloads_last_7_days = 0;
                $app->ratio = 0;
                return $app;
            }
    
            // Collect downloads for each of the last 7 days (from 7 days ago to yesterday)
            $downloads = [];
            $previousCount = null;
            $totalCount = 0;
    
            for ($i = 1; $i < 7; $i++) {
                $date = $startDate->copy()->addDays($i);
                $count = DB::table($app->app_table_name)
                    ->whereDate('created_at', $date)
                    ->count('dev_id');
    
                // Determine trend and percentage change
                $trend = 'same';
                $percentageChange = 0;
    
                if ($previousCount !== null) {
                    $trend = $count > $previousCount ? 'up' : ($count < $previousCount ? 'down' : 'same');
                    if ($previousCount > 0) {
                        $percentageChange = round((($count - $previousCount) / $previousCount) * 100, 2);
                    }
                }
    
                $downloads[$date->format('d-m-Y')] = [
                    'count' => $count,
                    'trend' => $trend,
                    'percentage_change' => $percentageChange,
                ];
                $totalCount += $count;
                $previousCount = $count; // Update previous count for the next iteration
            }
    
            // Calculate total downloads over the last 7 days
            $totalDownloadsLast7Days = $totalCount;
    
            // Calculate today’s downloads
            $todaysDownloads = DB::table($app->app_table_name)
                ->whereDate('created_at', $today)
                ->count('dev_id');
    
            // Calculate the 7-day average
            $averageDownloadsLast7Days = $totalDownloadsLast7Days > 0 ? round($totalDownloadsLast7Days / 7, 2) : 0;
    
            // Calculate the percentage increase or decrease
            if ($averageDownloadsLast7Days > 0) {
                $percentageChange = round((($todaysDownloads - $averageDownloadsLast7Days) / $averageDownloadsLast7Days) * 100, 2);
                $app->ratio = abs($percentageChange);
            } else {
                $app->ratio = 'N/A'; // When there is no data for the last 7 days
            }
    
            $app->percentageChange = $percentageChange;
    
            // Attach computed values to the app instance
            $app->todays_downloads = (int) $todaysDownloads;
            $app->total_downloads_last_7_days = (int) $totalDownloadsLast7Days;
            $app->average_downloads_last_7_days = $averageDownloadsLast7Days;
            $app->downloads = $downloads; // Store downloads with trends and percentage changes
    
            return $app;
        })->where('percentageChange',">=", 1)->sortByDesc('ratio')->values(); // Sort by ratio in ascending order to show highest decrease first
    
        // Return data to DataTables
        // Return data to DataTables
        return DataTables::of($categories)
            ->editColumn('created_at', function($app) {
                return date('d-m-Y', strtotime($app->created_at));
            })
            ->addColumn('app_type_android', function($app) {
                return empty($app->android_package_name) ? $app->ios_bundle_id : $app->android_package_name;
            })
            ->addColumn('average_downloads_last_7_days', function($app) {
                $average = round($app->total_downloads_last_7_days / 7);
                return "Total: {$app->total_downloads_last_7_days},<br>Average (last 7 days): {$average}";
            })
            ->addColumn('todays_downloads', function($app) {
                return $app->todays_downloads;
            })
            ->addColumn('ratio', function($app) {
                return $app->percentageChange > 1 ? 
                    $app->ratio ."% <i class='fa fa-solid fa-arrow-up text-success fa-lg'></i>" :  
                    $app->ratio ."% <i class='fa fa-solid fa-arrow-down text-danger fa-lg'></i>";
            })
            ->addColumn('daily_downloads', function($app) {
                $trendHtml = '<div class="space-c">';
                foreach ($app->downloads as $date => $data) {
                    $arrow = $data['trend'] === 'up' ? "<i class='fa fa-solid fa-arrow-up text-success'></i>" : 
                            ($data['trend'] === 'down' ? "<i class='fa fa-solid fa-arrow-down text-danger'></i>" : 
                            '<i class="fa fa-solid fa-arrow-right text-primary"></i>');
                    $color = $data['trend'] === 'up' ? 'green' : ($data['trend'] === 'down' ? 'red' : 'black');
                    $percentageText = $data['percentage_change'] != 0 ? "({$data['percentage_change']}%)" : '';
                    $trendHtml .= "<span style='color: {$color}'>{$date}: {$data['count']} {$arrow} {$percentageText}</span><br>";
                }
                $trendHtml .= "</div>";
                
                return $trendHtml;
            })
            ->rawColumns(['ratio', 'average_downloads_last_7_days', 'daily_downloads', 'app_type_android'])
            ->make(true);
    } 

}
