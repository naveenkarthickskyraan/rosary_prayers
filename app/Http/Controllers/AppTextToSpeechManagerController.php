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
use Maatwebsite\Excel\Facades\Excel;

class AppTextToSpeechManagerController extends Controller
{
    function show(request $request)
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
                    $disable = AppTextToSpeechManager::find($row);
                    $disable->is_deleted = 0;
                    $disable->save();
                }
                return back()->with('message','Record Enable Successfully');

            }
            else if($request->doAction == 'disable')
            {
                foreach($request->galleryIds as $row)
                {
                    $disable = AppTextToSpeechManager::find($row);
                    $disable->is_deleted = "1";
                    $disable->save();
                }
                return back()->with('message','Record Disabled Successfully');
            }  
            else if($request->doAction == 'delete')
            {
                foreach($request->galleryIds as $row)
                {
                    $disable = AppTextToSpeechManager::find($row);
                    $disable->is_deleted = 1;
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
                    $category = AppTextToSpeechManager::sortable(['id','desc'])->where('is_deleted','0')->where('is_deleted',$request->searchStatus)->Where("category_name", 'like', '%' . $search . '%')->paginate($action);
                    return view('admin.text_to_speech.text_to_speech_list', compact('search','category','action','searchStatus','searchBy'));
                }
                else
                {
                    $searchBy = $request->searchBy ?? '';
                    $searchStatus = $request->searchStatus ?? '';
                    $category = AppTextToSpeechManager::sortable(['id','desc'])->where('is_deleted','0')->Where("category_name", 'like', '%' . $search . '%')->paginate($action);
                    return view('admin.text_to_speech.text_to_speech_list', compact('search','category','action','searchStatus','searchBy'));
                }
            }
            else if($action != null)
            {
                $category = AppTextToSpeechManager::sortable(['id','desc'])->where('is_deleted','0')->paginate($action);
                return view('admin.text_to_speech.text_to_speech_list', ['category' => $category,'action' => $action]);
            }
            else
            {
                $category = AppTextToSpeechManager::sortable()->orderBy('id','desc')->where('is_deleted','0')->paginate(10);
                // dd($category);
                return view('admin.text_to_speech.text_to_speech_list', ['category' => $category]);
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
            return view('admin.text_to_speech.add_text_to_speech');
        }
        else
        {
            return redirect()->route('category.list')->with("error","Access Denied");
        }
    }

    public function store(Request $request)
    {
        $tts_name = $request->input('tts_name');
        $device_type_a = $request->input('device_type_a');
        $device_type_i = $request->input('device_type_i');
        $tts_language_code_android = $request->input('tts_language_code_android');
        $tts_language_code_ios = $request->input('tts_language_code_ios');
        $tts_identifier_ios = $request->input('tts_identifier_ios');
        $tts_artist_name_ios = $request->input('tts_artist_name_ios');
        
        // Validate the input data
        if (empty($tts_name)) {
            return back()->with('failer_message','Text to Speech Name is required');
        }
        
        if (empty($device_type_a) && empty($device_type_i)) {
            return back()->with('failer_message','Device Type (Android / iOS) is required');
        }
        
        if (!empty($device_type_a)) {
            if (empty($tts_language_code_android)) {
                return back()->with('failer_message','Android TTS language code is required');
            }
        }
        
        if (!empty($device_type_i)) {
            if (empty($tts_language_code_ios)) {
                return back()->with('failer_message','Text to Speech Language Code is required');
            }
            
            if (empty($tts_identifier_ios)) {
                return back()->with('failer_message','Text to Speech Identifier is required');
            }
        }
        
        // Prepare the data for insertion
        $device_type_a = $device_type_a == 1 ? 'Android' : null;
        $device_type_i = $device_type_i == 1 ? 'IOS' : '';
        
        // Insert the data into the database
        $cur_time = now()->toDateTimeString();
        $insertData = [
            'dev_type_android' => $device_type_a,
            'dev_type_ios' => $device_type_i,
            'tts_name' => $tts_name,
            'tts_language_code_ios' => $tts_language_code_ios,
            'tts_identifier_ios' => $tts_identifier_ios,
            'tts_artist_name_ios' => $tts_artist_name_ios,
            'tts_language_code_android' => $tts_language_code_android,
            'created_at' => $cur_time
        ];
        
        try {
            AppTextToSpeechManager::insert($insertData);
            return back()->with('success_message','Text to Speech Details Inserted Successfully!!');
        } catch (\Exception $e) {
            return back()->with('failer_message','Text to Speech Details Inserting Failed..');
        }
    }

    public function active(Request $request)
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
            $cat_id = $request->cat_id;
            $status = $request->status;
            if($status == 0)
            {
                $app_active = AppTextToSpeechManager::find($cat_id);
                $app_active->is_deleted ='1';
                $app_active->save();
                return back()->with('message','Category Disabled Successfully');
            }
            if($status == 1)
            {
                $app_active = AppTextToSpeechManager::find($cat_id);
                $app_active->is_deleted ='0';
                $app_active->save();
                return back()->with('message','Category Enabled Successfully');
            }
        }
        else
        {
            return redirect()->route('category.list')->with("error","Access Denied");
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
            $Q1 = AppTextToSpeechManager::find($id);
            $Q1->is_deleted = '1';
            $Q1->save();
            return back()->with('success_message', 'Text to Speech Details Deleted Successfully!!');
        }
        else
        {
            return redirect()->route('category.list')->with("error","Access Denied");
        }
    }

    public function EditCategory($id)
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
        $array_1= in_array('edit',$explode_1);
        if(($role == "SuperAdmin") || ($array  && $array_1== true))
        {
            $edit_app_id = AppTextToSpeechManager::find($id);
            return view('admin.text_to_speech.edit_text_to_speech_list', ['edit_category_id'=> $edit_app_id]);
        }
        else
        {
            return redirect()->route('category.list')->with("error","Access Denied");
        }
       
    }

    public function EditCatList(Request $request, $id)
    {
        $id = $id;
        $ttsName = $request->input('tts_name');
        $deviceTypeA = $request->input('device_type_a');
        $deviceTypeI = $request->input('device_type_i');
        $ttsLanguageCodeAndroid = $request->input('tts_language_code_android');
        $ttsLanguageCodeIos = $request->input('tts_language_code_ios');
        $ttsIdentifierIos = $request->input('tts_identifier_ios');
        $ttsArtistNameIos = $request->input('tts_artist_name_ios');

        if (empty($id)) {
            return back()->with('failer_message','Text to Speech ID is required');
        }

        if (empty($ttsName)) {
            return back()->with('failer_message','Text to Speech Name is required');
        }

        if (empty($deviceTypeA) && empty($deviceTypeI)) {
            return back()->with('failer_message','Device Type (Android / iOS) is required');
        }

        if (!empty($deviceTypeA)) {
            if (empty($ttsLanguageCodeAndroid)) {
                return back()->with('failer_message','Android TTS language code is required');
            }
        }

        if (!empty($deviceTypeI)) {
            if (empty($ttsLanguageCodeIos)) {
                return back()->with('failer_message','Text to Speech Language Code is required');
            }

            if (empty($ttsIdentifierIos)) {
                return back()->with('failer_message','Text to Speech Identifier is required');
            }
        }

        if ($deviceTypeA == 1) {
            $deviceTypeA = 'Android';
        } else {
            $deviceTypeA = null;
            $ttsLanguageCodeAndroid = null;
        }

        if ($deviceTypeI == 1) {
            $deviceTypeI = 'IOS';
        } else {
            $deviceTypeI = '';
            $ttsLanguageCodeIos = null;
            $ttsIdentifierIos = null;
            $ttsArtistNameIos = null;
        }

        if ($this->checkVoiceSpeechAudioName($ttsName, $deviceTypeA, $deviceTypeI, $id)) {
            $curTime = date('Y-m-d H:i:s');

            $actionQry = "UPDATE app_text_to_speech_manager SET
                            dev_type_android = '$deviceTypeA',
                            dev_type_ios = '$deviceTypeI',
                            tts_name = '$ttsName',
                            tts_language_code_ios = '$ttsLanguageCodeIos',
                            tts_identifier_ios = '$ttsIdentifierIos',
                            tts_artist_name_ios = '$ttsArtistNameIos',
                            tts_language_code_android = '$ttsLanguageCodeAndroid',
                            updated_at = '$curTime'
                            WHERE id = '$id'";
            
            $updateq = DB::update($actionQry);

            if ($updateq) {
                return back()->with('success_message', 'Text to Speech Details Updated Successfully!!');
            } else {
                return back()->with('failer_message','Text to Speech Details Updating Failed..');
            }
        } else {
            return back()->with('failer_message','Text to Speech Name already exists!!');
        }
    }
    private function checkVoiceSpeechAudioName($ttsName, $deviceTypeA, $deviceTypeI, $id)
    {
        $existingNameCount = DB::table('app_text_to_speech_manager')
            ->where('tts_name', $ttsName)
            ->where(function ($query) use ($deviceTypeA, $deviceTypeI) {
                $query->where('dev_type_android', $deviceTypeA)
                    ->orWhere('dev_type_ios', $deviceTypeI);
            })
            ->where('id', '!=', $id)
            ->count();

        return $existingNameCount === 0;
    }


    public function fileExport($category_id)
    {
        return Excel::download(new StudentExport($category_id), 'Letters-collection.xls');
    }
}
