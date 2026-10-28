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



class iosTeamIdController extends Controller
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
                return back()->with('error','Access Denied');
                foreach($request->galleryIds as $row)
                {
                    $disable = iosTeamId::find($row);
                    $disable->is_deleted = 0;
                    $disable->save();
                }
                return back()->with('message','Record Enable Successfully');

            }
            else if($request->doAction == 'disable')
            {
                return back()->with('error','Access Denied');
                foreach($request->galleryIds as $row)
                {
                    $disable = iosTeamId::find($row);
                    $disable->is_deleted = "1";
                    $disable->save();
                }
                return back()->with('message','Record Disabled Successfully');
            }  
            else if($request->doAction == 'delete')
            {
                return back()->with('error','Access Denied');
                foreach($request->galleryIds as $row)
                {
                    $disable = iosTeamId::find($row);
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
                    $category = iosTeamId::sortable(['id','desc'])->where('is_deleted',$request->searchStatus)->Where("category_name", 'like', '%' . $search . '%')->paginate($action);
                    return view('admin.iosconfig_team.iosconfig_team_list', compact('search','category','action','searchStatus','searchBy'));
                }
                else
                {
                    $searchBy = $request->searchBy ?? '';
                    $searchStatus = $request->searchStatus ?? '';
                    $category = iosTeamId::sortable(['id','desc'])->Where("category_name", 'like', '%' . $search . '%')->paginate($action);
                    return view('admin.iosconfig_team.iosconfig_team_list', compact('search','category','action','searchStatus','searchBy'));
                }
            }
            else if($action != null)
            {
                $category = iosTeamId::sortable(['id','desc'])->paginate($action);
                return view('admin.iosconfig_team.iosconfig_team_list', ['category' => $category,'action' => $action]);
            }
            else
            {
                $category = iosTeamId::sortable()->orderBy('id','desc')->paginate(10);
                return view('admin.iosconfig_team.iosconfig_team_list', ['category' => $category]);
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
            return view('admin.iosconfig_team.add_iosconfig_team');
        }
        else
        {
            return redirect()->route('category.list')->with("error","Access Denied");
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'color_code' => 'required',
            'color' => 'required',
        ]);

        $user = new iosTeamId;
        $user->category_name = $request['category_name'];
        $user->color_code = $request->color_code;
        $user->color = $request->color;
        $user->slug = str_replace(' ','-',strtolower($request['category_name']));
        if($request->file_type == 1)
        {
            $user->file_type = $request->file_type;
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
                $user->file_name = $uploaded_file;
            }
        }
        else if($request->file_type == 2)
        {
            $user->file_name = $request->url_image;
            $user->file_type = $request->file_type;
        }
        $user->save();
        return back()->with('message','Category Created Successfully');
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
                $app_active = iosTeamId::find($cat_id);
                $app_active->is_deleted ='1';
                $app_active->save();
                return back()->with('message','Category Disabled Successfully');
            }
            if($status == 1)
            {
                $app_active = iosTeamId::find($cat_id);
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
            $Q1 = iosTeamId::find($id);
            $Q1->is_deleted = '1';
            $Q1->save();
            return back()->with('message','Delete Record Successfully');
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
            $edit_app_id = iosTeamId::find($id);
            return view('admin.iosconfig_team.edit_iosconfig_team_list', ['edit_category_id'=> $edit_app_id]);
        }
        else
        {
            return redirect()->route('category.list')->with("error","Access Denied");
        }
       
    }

    public function EditCatList(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'color_code' => 'required',
            'color' => 'required',
        ]);


        $user = iosTeamId::find($id);

        $user->category_name = $request->input('category');
        $user->color_code = $request->color_code;
        $user->color = $request->color;
        $user->slug = str_replace(' ','-',strtolower($request['category']));

        if($request->file_type == 1)
        {
            $user->file_type = $request->file_type;
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
                $user->file_name = $uploaded_file;
            }
        }
        else if($request->file_type == 2)
        {
            $user->file_name = $request->url_image;
            $user->file_type = $request->file_type;
        }
        $user->save();
        if($user)
        {
            LogActivity::addToLog();
            return back()->with('message','Category Edit Successfully');
        }
        else
        {
            LogActivity::addToLog();
            return back()->with('error','Something Went Wrong');
        }
    }


    public function fileExport($category_id)
    {
        return Excel::download(new StudentExport($category_id), 'Letters-collection.xls');
    }
}
