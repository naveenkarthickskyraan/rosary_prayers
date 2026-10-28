<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;


use App\Models\AppList;
use App\Models\Module;
Use App\Models\Admin;
use App\Models\Category;
use App\Models\MappedAppCategory;
use App\Helpers\LogActivity;


class CategoryController extends Controller
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
                    $disable = Category::find($row);
                    $disable->is_active = 0;
                    $disable->save();
                }
                return back()->with('message','Record Enable Successfully');

            }
            else if($request->doAction == 'disable')
            {
                foreach($request->galleryIds as $row)
                {
                    $disable = Category::find($row);
                    $disable->is_active = "1";
                    $disable->save();
                }
                return back()->with('message','Record Disabled Successfully');
            }  
            else if($request->doAction == 'delete')
            {
                foreach($request->galleryIds as $row)
                {
                    $disable = Category::find($row);
                    $disable->deleted_at = 1;
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
                    $category = Category::orderBy('id','desc')->where('deleted_at','0')->where('is_active',$request->searchStatus)->Where("category_name", 'like', '%' . $search . '%')->paginate($action);
                    return view('admin.category.category_list', compact('search','category','action','searchStatus','searchBy'));
                }
                else
                {
                    $searchBy = $request->searchBy ?? '';
                    $searchStatus = $request->searchStatus ?? '';
                    $category = Category::orderBy('id','desc')->where('deleted_at','0')->Where("category_name", 'like', '%' . $search . '%')->paginate($action);
                    return view('admin.category.category_list', compact('search','category','action','searchStatus','searchBy'));
                }
            }
            else if($action != null)
            {
                $category = Category::orderBy('id','desc')->where('deleted_at','0')->paginate($action);
                return view('admin.category.category_list', ['category' => $category,'action' => $action]);
            }
            else
            {
                $category = Category::orderBy('id','desc')->where('deleted_at','0')->paginate(10);
                return view('admin.category.category_list', ['category' => $category]);
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
            return view('admin.category.add_category');
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

        $user = new Category;
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
                $app_active = Category::find($cat_id);
                $app_active->is_active ='1';
                $app_active->save();
                return back()->with('message','Category Disabled Successfully');
            }
            if($status == 1)
            {
                $app_active = Category::find($cat_id);
                $app_active->is_active ='0';
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
            $Q1 = Category::find($id);
            $Q1->deleted_at = '1';
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
            $edit = Category::find($id);
            $array = array($edit);
            return view('admin.category.edit_Category_list', ['edit_category_id'=> $array]);
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


        $user = Category::find($id);

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


    public function uploadFile(Request $request)
    {
        $file = $request->file('csvfile');
        
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();
        $valid_extension = array("csv");
    
        $maxFileSize = 4194304; 
        if(in_array(strtolower($extension),$valid_extension))
        {   
            if($fileSize <= $maxFileSize)
            {
                
                $location = 'uploads';
            
                $file->move($location,$filename);
        
                $filepath = public_path($location."/".$filename);
        
                $file = fopen($filepath,"r");
                
                $importData_arr = array();
                $i = 0;
        
                while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) 
                {
                    $num = count($filedata);        
                    
                    for ($c=0; $c < $num; $c++) 
                    {
                        $importData_arr[$i][] = $filedata[$c];
                    }
                    $i++;
                }
                fclose($file);
                $skip = 0;
                foreach($importData_arr as $key =>$importData)
                {
                    $insertData = array(
                        "letters" => $importData[0],
                    );  
                    LetterCount::create($insertData);
                }
                return back()->with('message','Import Successfully!.....');
            }
            else
            {
                return back()->with('error','File too large. File must be less than 4MB.');
            }
        }
        else
        {
            return back()->with('error','Invalid File Extension.');
        }
    }


    public function fileExport($category_id)
    {
        return Excel::download(new StudentExport($category_id), 'Letters-collection.xls');
    }
}
