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


class AudioManagerController extends Controller
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
                    $disable = AppsAudioManager::find($row);
                    $disable->is_deleted = 0;
                    $disable->save();
                }
                return back()->with("failer_message",'Record Enable Successfully');

            }
            else if($request->doAction == 'disable')
            {
                foreach($request->galleryIds as $row)
                {
                    $disable = AppsAudioManager::find($row);
                    $disable->is_deleted = "1";
                    $disable->save();
                }
                return back()->with("failer_message",'Record Disabled Successfully');
            }  
            else if($request->doAction == 'delete')
            {
                foreach($request->galleryIds as $row)
                {
                    $disable = AppsAudioManager::find($row);
                    $disable->is_deleted = 1;
                    $disable->save();
                }
                return back()->with("failer_message",'Record Delete Successfully');
            } 
            else if($search)
            {
                if(!empty($request->searchBy) && $request->searchStatus != null)
                {
                    $searchBy = $request->searchBy;
                    $searchStatus = $request->searchStatus;
                    $category = AppsAudioManager::sortable(['audio_app_id','desc'])->where('is_deleted','0')->where('is_deleted',$request->searchStatus)->Where("category_name", 'like', '%' . $search . '%')->paginate($action);
                    return view('admin.audio_manager.audio_manager_list', compact('search','category','action','searchStatus','searchBy'));
                }
                else
                {
                    $searchBy = $request->searchBy ?? '';
                    $searchStatus = $request->searchStatus ?? '';
                    $category = AppsAudioManager::sortable(['audio_app_id','desc'])->where('is_deleted','0')->Where("category_name", 'like', '%' . $search . '%')->paginate($action);
                    return view('admin.audio_manager.audio_manager_list', compact('search','category','action','searchStatus','searchBy'));
                }
            }
            else if($action != null)
            {
                $category = AppsAudioManager::sortable(['audio_app_id','desc'])->where('is_deleted','0')->paginate($action);
                return view('admin.audio_manager.audio_manager_list', ['category' => $category,'action' => $action]);
            }
            else
            {
                $category = AppsAudioManager::sortable(['audio_app_id','desc'])->where('is_deleted','0')->paginate(10);
                return view('admin.audio_manager.audio_manager_list', ['category' => $category]);
            }
        }
        else
        {
            return redirect()->route('admin/dashboard')->with("failer_message","Access Denied");
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
            return view('admin.audio_manager.add_audio_manager');
        }
        else
        {
            return redirect()->route('category.list')->with("failer_message","Access Denied");
        }
    }

    public function store(Request $request)
    {
        $audioAppName = $request->input('audio_app_name');
        $audioAppLanguage = $request->input('audio_app_language');
        $audioAppBasePath = $request->input('audio_app_base_path');
        $audioAppBasePathType = $request->input('audio_app_base_path_type');
        if (empty($audioAppName)) {
            return back()->with('failer_message', "Audio App Name is required");
        }

        if (empty($audioAppLanguage)) {
            return back()->with('failer_message',"Audio App Language is required");
        }

        if (empty($audioAppBasePath)) {
            return back()->with('failer_message', "Audio App Basepath is required");
        }

        if (empty($audioAppBasePathType)) {
            return back()->with('failer_message', "Audio App Basepath Type is required");
        }

        if ($this->checkAudioAppName($audioAppName, false)) {
            $curTime = \Carbon\Carbon::now();
            $actionQry = "INSERT INTO `apps_audio_manager` (audio_app_name, audio_app_language, audio_app_base_path, audio_app_base_path_type, created_at) 
                        VALUES (?, ?, ?, ?, ?)";

            $parameters = [$audioAppName, $audioAppLanguage, $audioAppBasePath, $audioAppBasePathType, $curTime];

            try {
                DB::insert($actionQry, $parameters);
                return back()->with("success_message" , "Audio App Details Inserted Successfully!!");
            } catch (\Exception $e) {
                return back()->with("failer_message","Audio App Details Inserting Failed..");
            }
        } else {
            return back()->with('failer_message',"Audio App Name already exists!!");
        }

    }

    private function checkAudioAppName($audioAppName, $audioAppId)
    {
        $result = DB::table('apps_audio_manager')
            ->where('audio_app_name', $audioAppName)
            ->where('is_deleted', 0);

        if ($audioAppId) {
            $result = $result->where('audio_app_id', '!=', $audioAppId);
        }

        $count = $result->count();
        return $count == 0;
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
                $app_active = AppsAudioManager::find($cat_id);
                $app_active->is_deleted ='1';
                $app_active->save();
                return back()->with("success_message",'Category Disabled Successfully');
            }
            if($status == 1)
            {
                $app_active = AppsAudioManager::find($cat_id);
                $app_active->is_deleted ='0';
                $app_active->save();
                return back()->with("success_message",'Category Enabled Successfully');
            }
        }
        else
        {
            return redirect()->route('category.list')->with("failer_message","Access Denied");
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
            $Q1 = AppsAudioManager::find($id);
            $Q1->is_deleted = '1';
            $Q1->save();
            return back()->with("success_message",'Delete Record Successfully');
        }
        else
        {
            return redirect()->route('category.list')->with("failer_message","Access Denied");
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
            $edit_app_id = AppsAudioManager::find($id);
            return view('admin.audio_manager.edit_audio_manager_list', ['edit_category_id'=> $edit_app_id]);
        }
        else
        {
            return redirect()->route('category.list')->with("failer_message","Access Denied");
        }
       
    }

    public function EditCatList(Request $request, $id)
    {
        
        $audio_app_id = $id;
        $audioAppName = $request->input('audio_app_name');
        $audioAppLanguage = $request->input('audio_app_language');
        $audioAppBasePath = $request->input('audio_app_base_path');
        $audioAppBasePathType = $request->input('audio_app_base_path_type');
        if (empty($audioAppName)) {
            return back()->with('failer_message', "Audio App Name is required");
        }

        if (empty($audioAppLanguage)) {
            return back()->with('failer_message',"Audio App Language is required");
        }

        if (empty($audioAppBasePath)) {
            return back()->with('failer_message', "Audio App Basepath is required");
        }

        if (empty($audioAppBasePathType)) {
            return back()->with('failer_message', "Audio App Basepath Type is required");
        }


        if ($this->checkAudioAppName($audioAppName, $audio_app_id)) {
            $cur_time = date('Y-m-d H:i:s');
            $actionQry = "UPDATE `apps_audio_manager` SET
                            audio_app_name = '$audioAppName',
                            audio_app_language = '$audioAppLanguage',
                            audio_app_base_path = '$audioAppBasePath',
                            audio_app_base_path_type = '$audioAppBasePathType',
                            updated_at = '$cur_time' 
                            WHERE audio_app_id='$audio_app_id'";

            try {
                $updateq = DB::statement($actionQry);
                return back()->with("success_message" , "Audio App Details Updated Successfully!!");
            } catch (\Exception $e) {
                return back()->with("failer_message","Audio App Details Updating Failed..");
            }
        } else {
            return back()->with("failer_message","Audio App Name already exists!!");
        }
    }

    public function fileExport($category_id)
    {
        return Excel::download(new StudentExport($category_id), 'Letters-collection.xls');
    }
}
