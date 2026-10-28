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
use App\Models\NotificationTemplate;
use App\Models\Language;
use App\Models\Country;
use App\Models\PushNotifyApp;
use Illuminate\Support\Facades\Storage;
use App\Imports\AppImport;
use App\DataTables\AppDataTable;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use DataTables;

class SupportController extends Controller
{
    public function ajaxLoadData(Request $request)
    {
        $app_id = array();
        $app_id = explode(",", request('app_id'));
        
        if (count($app_id) == 1) {

            $app_id = $app_id[0];

            $show_dev_type = '';

            $app = App::where('app_id', $app_id)
                        ->where('push_notification_app', 'Enable')
                        ->first();
            
            $app_table_name = $app->app_table_name;

            $column = 'dev_type';
            $devTypes = DB::table($app_table_name)->select($column)->distinct()->get();

            if($devTypes->isNotEmpty()) {
                // $show_dev_type = '<option value="">Select Device Type</option>'; 
                foreach ($devTypes as $devType) {
                    if($devType->dev_type == '1') {
                        $show_dev_type .= '<option value="'.$devType->dev_type.'">Android</option>';
                    }
                    
                    if($devType->dev_type == '2') {
                        $show_dev_type .='<option value="'.$devType->dev_type.'">iOS</option>';   
                    }
                    
                    if($devType->dev_type == '0') {
                        $show_dev_type .= '<option value="">Device Type not available</option>'; 
                    }
                }
            } else {
                $show_dev_type .= '<option value="">Devices Type not available</option>'; 
            }
            
            // For appDeviceId
            $column = 'appDeviceId';
            $show_appDeviceId = '';
            $appDeviceIds = DB::table($app_table_name)->select($column)->distinct()->limit(1000)->get();

            if ($appDeviceIds->isNotEmpty()) {
                foreach ($appDeviceIds as $appDeviceId) {
                    if ($appDeviceId->appDeviceId != '') {
                        $show_appDeviceId .= '<option value="'.$appDeviceId->appDeviceId.'">'.$appDeviceId->appDeviceId.'</option>';
                    }

                    if ($appDeviceId->appDeviceId == '0' && $appDeviceId->appDeviceId == '') {
                        $show_appDeviceId .= '<option value="">App Device ID data not available</option>';
                    }
                }
            } else {
                $show_appDeviceId .= '<option value="">App Device ID data not available</option>';
            }

            // For country_code
            $column = 'country_code';
            $show_country_code = '';
            $countryCodes = DB::table($app_table_name.' as t')
                ->select('t.'.$column, 'c.nicename')
                ->join('country as c', 't.country_Code', '=', 'c.iso')
                ->distinct()
                ->get();

            if ($countryCodes->isNotEmpty()) {
                foreach ($countryCodes as $countryCode) {
                    if ($countryCode->country_code != '') {
                        $temp = $countryCode->nicename;
                        $show_country_code .= '<option value="'.$countryCode->country_code.'">'.$temp.'</option>';
                    }

                    if ($countryCode->country_code == '0' && $countryCode->country_code == '') {
                        $show_country_code .= '<option value="">Country data not available</option>';
                    }
                }
            } else {
                $show_country_code .= '<option value="">Country data not available</option>';
            }

            // For language
            $column = 'language';
            $show_language = '';
            $languages = DB::table($app_table_name.' as t')
                ->select('t.'.$column, 'lan.name')
                ->join('languages as lan', 't.language', '=', 'lan.iso_639')
                ->distinct()
                ->get();

            if ($languages->isNotEmpty()) {
                foreach ($languages as $language) {
                    if ($language->language != '') {
                        $show_language .= '<option value="'.$language->language.'">'.$language->name.'</option>';
                    }

                    if ($language->language == '0' && $language->language == '') {
                        $show_language .= '<option value="">Language data not available</option>';
                    }
                }
            } else {
                $show_language .= '<option value="">Language data not available</option>';
            }

            // For app_version
            $column = 'app_version';
            $show_app_version = '';
            $appVersions = DB::table($app_table_name)->select($column)->distinct()->get();

            if ($appVersions->isNotEmpty()) {
                foreach ($appVersions as $appVersion) {
                    if ($appVersion->app_version != '') {
                        $show_app_version .= '<option value="'.$appVersion->app_version.'">'.$appVersion->app_version.'</option>';
                    }

                    if ($appVersion->app_version == '0' && $appVersion->app_version == '') {
                        $show_app_version .= '<option value="">App Version data not available</option>';
                    }
                }
            } else {
                $show_app_version .= '<option value="">App Version data not available</option>';
            }

            // For appMode
            $column = 'appMode';
            $show_appMode = '';
            $appModes = DB::table($app_table_name)->select($column)->distinct()->get();

            if ($appModes->isNotEmpty()) {
                foreach ($appModes as $appMode) {
                    if ($appMode->appMode == 1) {
                        $show_appMode .= '<option value="1" selected>Development</option>';
                    }
                    if ($appMode->appMode == 0) {
                        $show_appMode .= '<option value="2">Production</option>';
                    }

                    if ($appMode->appMode == '') {
                        $show_appMode .= '<option value="">App Mode data not available</option>';
                    }
                }
            } else {
                $show_appMode .= '<option value="">App Mode data not available</option>';
            }

            // For dev_version
            $column = 'dev_version';
            $show_device_version = '';
            $deviceVersions = DB::table($app_table_name)->select($column)->distinct()->get();

            if ($deviceVersions->isNotEmpty()) {
                foreach ($deviceVersions as $deviceVersion) {
                    if ($deviceVersion->dev_version != '') {
                        $show_device_version .= '<option value="'.$deviceVersion->dev_version.'">'.$deviceVersion->dev_version.'</option>';
                    }

                    if ($deviceVersion->dev_version == '0' && $deviceVersion->dev_version == '') {
                        $show_device_version .= '<option value="">Device Version data not available</option>';
                    }
                }
            } else {
                $show_device_version .= '<option value="">Device Version data not available</option>';
            }

            $options = [
                'devType' => $show_dev_type,
                'appDeviceId' => $show_appDeviceId,
                'country_code' => $show_country_code,
                'language' => $show_language,
                'app_version' => $show_app_version,
                'appMode' => $show_appMode,
                'device_version' => $show_device_version,
            ];
            
            $html = '';
            $html1 = '';
            
            foreach ($options as $field => $value) {
                if ($field == "appMode") {
                    $html .= '<div class="col-md-6 mb-3">
                            <select class="form-select input-full" id="' . $field . '" data-placeholder="Select ' . ucfirst(str_replace('_', ' ', $field)) . '">
                                ' . $value . '  
                            </select>
                        </div>
                        <br>';
            
                } else {
                    $html .= '<div class="col-md-6 mb-3">
                            <select multiple class="form-select input-full" id="' . $field . '" data-placeholder="Select ' . ucfirst(str_replace('_', ' ', $field)) . '">
                                ' . $value . '
                            </select>
                        </div>
                        <br>';
                }
            }
            
            $html1 .= '<div class="row">
                            ' . $html . '
                            <div class="col-md-6">
                                <select  class="form-select input-full" id="activity_time" data-placeholder="Select by User Activity Days">
                                    <option value="" >Select User Activity Time</option>
                                    <!--<option value="7days">Last Week 7 Days</option>-->
                                    <!--<option value="31days">Last 31 Days</option>-->
                                    <option value="Today">Today</option>
                                    <option value="7days">Last Week</option>
                                    <option value="31days">Last Month</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <button class="btn btn-success btn-sm" type="button" id="filter_submit" onclick="filter_Apply()" name="filter_submit">Apply</button>
                                <button class="btn btn-info btn-sm" type="button" id="filter_reset" name="filter_reset" onclick="filter_Reset()">Reset</button>
                            </div>
                        </div>';       
                             
            echo $html1;
        }
        elseif(count($app_id) > 1){ 
                        
            // Fetch country options
            $countryOptions = Country::orderBy('nicename')->pluck('nicename', 'iso');
            $show_country_code = $this->BulkGenerateOptions($countryOptions,'Country data not available');

            // Fetch language options
            $languageOptions = Language::orderBy('name')->pluck('name', 'iso_639');
            $show_language = $this->BulkGenerateOptions($languageOptions,'Language data not available');
            $html = '
                <div class="row">
                    <div class="col-md-6">
                        <select class="form-control input-full" id="appMode" required>
                            <option value="">Select App Mode</option>
                            <option value="1" selected>Development</option>
                            <option value="2">Production</option>
                        </select>
                        <small id="app_mode_err" class="form-text text-muted" style="color: #dc3545!important;"></small>
                    </div>
                    <div class="col-md-6">
                        <select multiple class="form-control input-full" id="devType" data-placeholder="Select Device Type">
                            <option value="1">Android</option>
                            <option value="2">IOS</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <select multiple class="form-control input-full" id="country_code" data-placeholder="Select Country Name">
                            ' . $show_country_code . '
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select multiple class="form-control input-full" id="language" data-placeholder="Select Language">
                            ' . $show_language . '
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <select class="form-control input-full" id="activity_time" data-placeholder="Select by User Activity Days">
                            <option value="">Select User Activity Time</option>
                            <option value="Today">Today</option>
                            <option value="7days">Last Week</option>
                            <option value="31days">Last Month</option>
                        </select>
                    </div>
                </div>
                <br>
                <br>
                <div class="row">
                    <div class="col-sm-6 col-md-4">
                        <button class="btn btn-success btn-sm" type="button" id="filter_submit" onclick="filter_Apply()" name="filter_submit">Apply</button>
                        <button class="btn btn-info btn-sm" type="button" id="filter_reset" name="filter_reset" onclick="filter_Reset()">Reset</button>
                    </div>
                </div>';

            echo $html;
        }
        elseif(count($app_id) == 0)
        {
            echo 'App Id is 0';
        }
        else
        {
            echo 'Invalid App Id';
        }
    }

    private function generateOptions($query, $column, $label, $emptyMessage) {
        $options = '';
        $result = DB::select($query);
        if (count($result) > 0) {
            foreach ($result as $row) {
                if ($row->$column != '') {  
                    $options .= '<option value="' . $row->$column . '">' . $row->$label . '</option>';
                }
            }
        } else {
            $options .= '<option value="">' . $emptyMessage . '</option>';
        }
    
        return $options;
    }

    private function BulkGenerateOptions($options, $emptyMessage)
    {
        $generatedOptions = '';

        if ($options->isNotEmpty()) {
            foreach ($options as $value => $label) {
                $generatedOptions .= '<option value="' . $value . '">' . $label . '</option>';
            }
        } else {
            $generatedOptions .= '<option value="">' . $emptyMessage . '</option>';
        }

        return $generatedOptions;
    }

    public function applyFilter(Request $request)
    {
        if (!empty(request()->input("app_id_filter")) && !empty(request()->input("filter_apply_all_apps"))) {
            $app_id = explode(",", request()->input('app_id_filter'));
            $app_ids_length = request()->input('app_ids_length');
            $appMode = request()->input('appMode', '');
            $devType = request()->input('devType', '');
            $country_code = request()->input('country_code', '');
            $language = request()->input('language', '');
            $device_version = request()->input('device_version', '');
            $app_version = request()->input('app_version', '');
            $appDeviceId = request()->input('appDeviceId', '');
            $activity_time = request()->input('activity_time', '');
            $notify_Id_f = 0;
            $appMode_f = $appMode;

            if (!empty($appMode) && $appMode != "") {
                if ($appMode == "1" || $appMode == 1) {
                    $appMode_f = 'Development';
                } elseif ($appMode == "2" || $appMode == 2) {
                    $appMode_f = 'Production';
                }
            }

            $devType_f = serialize($devType);
            $language_f = serialize($language);
            $country_code_f = serialize($country_code);
            $device_version_f = serialize($device_version);
            $app_version_f = serialize($app_version);
            $appDeviceId_f = serialize($appDeviceId);
            $activity_time_f = serialize($activity_time);

            $cur_time = now();
            $actionQry = "INSERT INTO `notification_filter_data` (`n_id`, `appMode`, `deviceType`, `deviceVersion`, `appVersion`, `language`, `countryCode`, `appDeviceId`, `activity_time`, `created_at`) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $insQry = DB::insert($actionQry, [$notify_Id_f, $appMode_f, $devType_f, $device_version_f, $app_version_f, $language_f, $country_code_f, $appDeviceId_f, $activity_time_f, $cur_time]);
            $filter_id = DB::getPdo()->lastInsertId();

            $resArr = [];
            $resArrApp = [];

            $result = App::select('app_table_name', 'app_id')
                            ->where('app_deleted', '0')
                            ->whereIn('app_id', $app_id)
                            ->where('push_notification_app', 'Enable')
                            ->get();
            $dev_ids = '';
            $total_count = 0;
            $i = 0;
            $selQry = '';
            $select = '';
            $table_name = '';

            if (count($result) > 0) {
                foreach ($result as $row) {
                    $set_table = ($row->app_table_name == "" || $row->app_table_name == NULL) ? 'nil' : $row->app_table_name;
                    if ($set_table != 'nil') {
                        $select .= $row->app_table_name . ".dev_id," . $row->app_table_name . ".dev_app_id";
                        $table_name .= "`" . $row->app_table_name . "`";

                        if (count($result) == 1) {
                            $selQry .= "(SELECT " . $select . " FROM " . $table_name;
                            $selQry .= $this->buildQueryAllApps($table_name, $appMode, $devType, $country_code, $language, $device_version, $app_version, $appDeviceId, $activity_time);
                            $selQry .= ") ";
                        } else {
                            if (count($result) == ($i + 1)) {
                                $selQry .= "(SELECT " . $select . " FROM " . $table_name;
                                $selQry .= $this->buildQueryAllApps($table_name, $appMode, $devType, $country_code, $language, $device_version, $app_version, $appDeviceId, $activity_time);
                                $selQry .= ") ";
                                $select = "";
                                $table_name = "";
                            } else {
                                $selQry .= "(SELECT " . $select . " FROM " . $table_name;
                                $selQry .= $this->buildQueryAllApps($table_name, $appMode, $devType, $country_code, $language, $device_version, $app_version, $appDeviceId, $activity_time);
                                $selQry .= ") ";
                                $selQry .= "UNION ALL ";

                                $select = "";
                                $table_name = "";
                            }
                        }
                    }

                    $i++;
                }

                $temp_app_id = '';
                if ($selQry != '') {
                    $result = DB::select($selQry);
                    $total_count = count($result);
                    if (count($result) > 0) {
                        foreach ($result as $row) {
                            if ($i == 0) {
                                $temp_app_id = $row->dev_app_id;
                            }
                            if ($row->dev_app_id != $temp_app_id) {
                                $temp_app_id = $row->dev_app_id;
                            }
                            $resArr[$temp_app_id][$i] = $row->dev_id;
                            $i++;
                        }
                    }
                    // $dev_ids = json_encode($resArr);
                    $dev_ids = serialize($resArr);
                }

                $data['exe_query'] = $selQry;
                $data['dev_ids'] = $dev_ids;
                $data['dev_ids_total'] = $total_count;
                $data['filter_id'] = $filter_id;

                echo json_encode($data);
            } else {
                $data['exe_query'] = $selQry;
                $data['dev_ids'] = $dev_ids;
                $data['dev_ids_total'] = $total_count;
                $data['filter_id'] = $filter_id;

                echo json_encode($data);
            }

            exit;
        }
    }

    private function buildQueryAllApps($table_name, $appMode, $devType, $country_code, $language, $device_version, $app_version, $appDeviceId, $activity_time)
    {
        $queryParts = [];

        if (!empty($appMode) && $appMode != "") {
            $queryParts[] = $table_name . ".`appMode` = '" . ($appMode == 1 ? 1 : 0) . "'";
        }

        if (!empty($devType) && $devType[0] != "") {
            $queryParts[] = $table_name . ".`dev_type` IN ('" . implode("','", $devType) . "')";
        }

        if (!empty($country_code) && $country_code[0] != "") {
            $queryParts[] = $table_name . ".`country_code` IN ('" . implode("','", $country_code) . "')";
        }

        if (!empty($language) && $language[0] != "") {
            $queryParts[] = $table_name . ".`language` IN ('" . implode("','", $language) . "')";
        }

        if (!empty($device_version) && $device_version[0] != "") {
            $queryParts[] = "`dev_version` IN ('" . implode("','", $device_version) . "')";
        }

        if (!empty($appDeviceId) && $appDeviceId[0] != "") {
            $queryParts[] = "`appDeviceId` IN ('" . implode("','", $appDeviceId) . "')";
        }

        if (!empty($app_version) && $app_version[0] != "") {
            $queryParts[] = "`app_version` IN ('" . implode("','", $app_version) . "')";
        }

        if (!empty($activity_time)) {
            $activityQuery = '';
            if ($activity_time == "7days") {
                $activityQuery = $table_name . ".`activity_time` >= CURDATE() - INTERVAL 7 DAY";
            } elseif ($activity_time == "31days") {
                $activityQuery = $table_name . ".`activity_time` >= CURDATE() - INTERVAL 31 DAY";
            } elseif ($activity_time == "Today") {
                $activityQuery = $table_name . ".`activity_time` >= CURDATE()";
            } else {
                $activityQuery = $table_name . ".`activity_time` >= CURDATE() - INTERVAL 31 DAY";
            }
            $queryParts[] = $activityQuery;
        }

        if (count($queryParts) > 0) {
            $query = " WHERE " . implode(" AND ", $queryParts);
        } else {
            $query = "";
        }

        return $query;
    }


    //tecmplate id get 
    public function getTemplateData(Request $request)
    {
        $id = $request->input('template_id_ajax');

        $template = NotificationTemplate::where('id', $id)
            ->where('is_deleted', 0)
            ->first();

        if (!$template) {
            $template = [
                'n_title' => 'nil',
                'n_text' => 'nil',
                'n_img_upload_type' => 'nil',
                'n_image' => 'nil',
                'n_name' => 'nil'
            ];
        }

        return response()->json($template);
    }


}
