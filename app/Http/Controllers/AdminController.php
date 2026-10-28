<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon;


use App\Helpers\LogActivity;
use App\Models\App;
use App\Models\Module;
Use App\Models\admin;
use App\Models\BannerImageType;
use App\Models\BannerImage;
use App\Models\MappedAppCategory;
use App\Models\Word;
use Hash;
use Illuminate\Support\Facades\Response;


class AdminController extends Controller
{
   public function authenticate(Request $request)
{
    $this->validate($request, [
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $app_active = admin::where('email', $request->email)->get();

    if (count($app_active) && !empty($app_active)) {

        foreach ($app_active as $user) {

            if ($user->is_active == 0 && $user->deleted_at == 0) {

                if (Auth::guard('admin')->attempt(
                    [
                        'email' => $request->email,
                        'password' => $request->password
                    ],
                    $request->get('remember')
                )) {

                    LogActivity::addToLog();

                    toastr()->success("Welcome - " . $request->email);

                    // Login success → Dashboard
                    return redirect()->route('admin/dashboard');

                } else {

                    session()->flash(
                        'error',
                        'Either Email/Password is incorrect - Please Try Again'
                    );

                    return back()->withInput(
                        $request->only('email')
                    );
                }

            } else {

                session()->flash(
                    'error',
                    "You can't access this site right now!.."
                );

                return back()->withInput(
                    $request->only('email')
                );
            }
        }

    } else {

        session()->flash(
            'error',
            'Either Email/Password is incorrect - Please Try Again'
        );

        return back()->withInput(
            $request->only('email')
        );
    }
}

    public function login_check()
    {
        if(Auth::guard('admin')->check() == true)
        {
            return Redirect::to('admin/dashboard');
        }
        else
        {
            return Redirect::to('admin/login');
        }
    }

    public function logout()
    {
        LogActivity::addToLog();
        Auth::guard('admin')->logout();
        toastr()->success("Thank you getting back to us!...");
        return redirect()->route('admin.login')->with('message','Logout Successfully');
    }

    public function dashboard(Request $request)
    {
        
    return view('admin.dashboard');
    }

    public function get_main_page_list(Request $request)
    {
        if ($request->ajax()) {
            $date = date('Y-m-d');

            // Fetch all apps
            $TotalAppList = App::where('app_deleted', 0)->get();

            // Fetch total row count and count of devices added today for each app
            $TotalRowCount = 0;
            $TotalUsersTodayCount = 0;

            foreach ($TotalAppList as $app) {
                $result = DB::table($app->app_table_name)
                    ->select(
                        DB::raw('COUNT(dev_id) as total_devices'),
                        DB::raw('SUM(CASE WHEN `created_at` LIKE "%' . date('Y-m-d') . '%" THEN 1 ELSE 0 END) AS today_added_count')
                    )
                    ->get();

                $rowCount = $result[0]->total_devices ?? 0; // assuming 'total_devices' is the column name in your table
                $total_users_today = $result[0]->today_added_count ?? 0;

                $TotalUsersTodayCount += $total_users_today;
                $TotalRowCount += $rowCount;
            }

            // Count of apps
            $totalApps = $TotalAppList->count();

            // Count of today's apps
            $todayAppCreated = App::whereDate('created_at', DB::raw('CURDATE()'))->count();

            return response()->json([
                'app_list' => $todayAppCreated,
                'totalRowCount' => $TotalRowCount,
                'admin' => $TotalUsersTodayCount,
                'all' => $totalApps
            ]);
        }
    }

    public function profileUpdate(Request $request)
    {
        LogActivity::addToLog();
        $request->validate([
            'name' =>'required|min:8|string|max:25',
            'email'=>'required|email|string|max:255',
        ]);
        $user = Auth::guard('admin')->user();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->image = $request['file'];
        if($request->password != null)
        {
            $request->validate(
                [
                    "password" => 'required|confirmed|min:8',
                ],[
                'password.min'=>'confirm-password length must be greater than 8 characters',
            ]);
            $user->password = Hash::make($request['password']);
        }
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
            $image->move(public_path('storage/files/user'),$new_name);
            $new_name_2= 'storage/files/user/'.$new_name;
        }
        $uploaded_file = $new_name_2 ?? '';
        $user->image = $uploaded_file;
        $user->save();
        return back()->with('message','Profile Updated Successfully');


    }

    public function superadmin(request $request)
    {
        $user_id =Auth::guard('admin')->user()->id;
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
        $array = in_array('admin', $permis);
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
                    $disable = admin::find($row);
                    $disable->is_active = 0;
                    $disable->save();
                }
                toastr()->success('Record Enable Successfully!');
                return back();

            }
            else if($request->doAction == 'disable')
            {
                foreach($request->galleryIds as $row)
                {
                    $disable = admin::find($row);
                    $disable->is_active = "1";
                    $disable->save();
                }
                return back()->with('message','Record Disabled Successfully');
            }
            else if($request->doAction == 'delete')
            {
                foreach($request->galleryIds as $row)
                {
                    $disable = admin::find($row);
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
                    $superadmin = admin::orderBy('role','asc')->where('role','SubAdmin')->where('deleted_at','0')->where('is_active',$request->searchStatus)->Where("name", 'like', '%' . $search . '%')->paginate($action);
                    return view('admin.accounts.admin_accounts', compact('search','superadmin','action','searchStatus','searchBy'));
                }
                else
                {
                    $searchBy = $request->searchBy ?? '';
                    $searchStatus = $request->searchStatus ?? '';
                    $superadmin = admin::orderBy('role','asc')->where('role','SubAdmin')->where('deleted_at','0')->Where("name", 'like', '%' . $search . '%')->orWhere("email", 'like', '%' . $search . '%')->orWhere("role", 'like', '%' . $search . '%')->paginate($action);
                    return view('admin.accounts.admin_accounts', compact('search','superadmin','action','searchStatus','searchBy'));
                }
            }
            else if($action != null)
            {
                $superadmin = admin::orderBy('role','asc')->where('role','SubAdmin')->where('deleted_at','0')->paginate($action);
                return view('admin.accounts.admin_accounts', ['superadmin' => $superadmin,'action' => $action]);
            }
            else
            {
                $superadmin = admin::orderBy('role','asc')->where('role','SubAdmin')->where('deleted_at','0')->paginate(10);
                return view('admin.accounts.admin_accounts', ['superadmin' => $superadmin]);
            }
        }
        else
        {
            return redirect()->route('admin/dashboard')->with("error","Access Denied");
        }
    }

    function add(Request $request)
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
        $array = in_array('admin', $permis);
        $array_1= in_array('add',$explode_1);
        if(($role == "SuperAdmin") || ($array == true &&  $array_1 == true))
        {
            $module = Module::all();
            return view('admin.accounts.add_admmin',['module'=>$module]);
        }
        else
        {
            return redirect()->route('admin/dashboard')->with("error","Access Denied");
        }
    }

    function insert(Request $request)
    {

        $request->validate([
            'name' =>'required|min:8|string|max:25',
            'email'=>'required|email|string|max:255',
            "password" => 'required|confirmed|min:8'
        ],
        [
            'password.same'=>'password are not the same password must match same value',
            'password.min'=>'confirm-password length must be greater than 8 characters',
        ]);
        $user = new admin;
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->image = $request['file'];
        $user->password = Hash::make($request['password']);

        $arrays = implode("|", $request->model);
        $user->accountAccessModule = $arrays ?? '';
        $accountAccessPrivilege = implode("|", $request->privileges);
        $user->accountAccessPrivilege = $accountAccessPrivilege ?? '';

        $user->role = $request->parent;
        if($request->hasFile('file'))
        {
            $destination_path = 'public/files/user';
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
            $user->image = $uploaded_file;
        }
        LogActivity::addToLog();
        $user->save();
        return back()->with('message','Admin Create Successfully');
    }

    public function edit($id)
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
        $array = in_array('admin', $permis);
        $array_1= in_array('edit',$explode_1);
        if(($role == "SuperAdmin") || ($array == true &&  $array_1 == true))
        {
            $edit = admin::find($id);
            $array = array($edit);
            $module = Module::all();
            return view('admin.accounts.edit_admin', ['edit'=> $array,'module'=>$module]);
        }
        else
        {
            return redirect()->route('superadmin')->with("error","Access Denied");
        }
    }

    public function editlist(Request $request,$id)
    {

        $request->validate([
            'name' =>'required|min:8|string|max:25',
            'email'=>'required|email|string|max:255',
        ]);
        $user = admin::find($id);
        $user->role = $request->parent;
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->image = $request['file'];
        if($request->password != null)
        {
            $request->validate([ "password" => 'required|confirmed|min:8'
            ],[
                'password.same'=>'password are not the same password must match same value',
                'password.min'=>'confirm-password length must be greater than 8 characters',
            ]);
            $user->password = Hash::make($request['password']);
        }

        $arrays = implode("|", $request->model);
        $user->accountAccessModule = $arrays ?? '';
        $accountAccessPrivilege = implode("|", $request->privileges);
        $user->accountAccessPrivilege = $accountAccessPrivilege ?? '';
        $user->role = $request->parent;
        if($request->file_type == 1)
        {
            $user->image_type = $request->file_type;
            if($request->hasFile('file'))
            {
                $destination_path = 'public/files/user';
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
                $user->image = $uploaded_file;
            }
        }
        else if($request->file_type == 2)
        {
            $user->image = $request->url_image;
            $user->image_type = $request->file_type;
        }
        LogActivity::addToLog();
        $user->save();
        return back()->with('message','Admin Edit Successfully');
    }

    public function active(Request $request)
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
            $parent_id = $request->user_id;
            $status = $request->status;
            LogActivity::addToLog();
            if($status == 0)
            {
                $app_active = admin::find($parent_id);
                $app_active->is_active ='1';
                $app_active->save();
                return back()->with('message','User Disabled Successfully');
            }
            if($status == 1)
            {
                $app_active = admin::find($parent_id);
                $app_active->is_active ='0';
                $app_active->save();
                return back()->with('message','User Enabled Successfully');
            }
        }
        else
        {
            return redirect()->route('superadmin')->with("error","Access Denied");
        }
    }

    public function delete(Request $request, $id)
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
        $array = in_array('admin', $permis);
        $array_1= in_array('delete',$explode_1);
        if(($role == "SuperAdmin") || ($role == "Administrator") || ($array == true &&  $array_1 == true))
        {
            $Q1 = admin::find($id);
            $Q1->deleted_at = '1';
            LogActivity::addToLog();
            $Q1->save();
            return back()->with('message','Delete Record Successfully');
        }
        else
        {
            return redirect()->route('superadmin')->with("error","Access Denied");
        }
    }

    public function activitydelete(Request $request)
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
        $array = in_array('Activity', $permis);
        $array_1= in_array('delete',$explode_1);
        if(($role == "SuperAdmin") || ($role == "Administrator") || ($array == true &&  $array_1 == true))
        {
            // DB::delete('delete users where name = ?', ['John']);
            $log = LogActivity::logActivityLists()->find($request->checkbox)
            ->each(function ($activity, $key)
            {
                $activity->delete();
            });
            LogActivity::addToLog();
            return back()->with('message','Delete Record Successfully');
        }
        else
        {
            return redirect()->route('superadmin')->with("error","Access Denied");
        }
    }

    public function upload(Request $request)
    {

        if($request->hasFile('upload'))
        {
            $image = $request->file('upload');
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
            $image->move(public_path('upload'),$new_name);
            $new_name_2 = 'upload/'.$new_name;


            $url = asset($new_name_2);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $msg = 'Image successfully uploaded';
            LogActivity::addToLog();
            $re = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
            @header('Content-type: text/html; charset=utf-8');
            echo $re;
        }
    }
}

