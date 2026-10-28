<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon;


use App\Models\AppList;
use App\Models\Module;
Use App\Models\Admin;
use App\Models\Category;
Use App\Models\CalendarImage;
use App\Models\Prayer;
use App\Models\BannerImageType;
use App\Models\BannerImage;
use App\Models\MappedAppCategory;
use App\Helpers\LogActivity;

use App\Models\DailyRasiPalan;
use App\Models\EnglMonthToTamilMonth;
use App\Models\FestivalList;
use App\Models\GowriNallaNeram;
use App\Models\GowriPanjangam;
use App\Models\ImportantDays;
use App\Models\KakaPalan;
use App\Models\kanavuPalangal;
use App\Models\Kerangal;
use App\Models\kanavupalangalCategory;
use App\Models\KiragaOraikalinKalam;
use App\Models\Kocharam;
use App\Models\Laknam;
use App\Models\MachaPalangal;
use App\Models\Moogurtham;
use App\Models\NatchathiralNatkal;
use App\Models\PalliSollumPalan;
use App\Models\PalliVillumPalan;
use App\Models\Parigaram;
use App\Models\RaaguKuligaiEmagandam;
use App\Models\Soolam;
use App\Models\Stars;
use App\Models\Thithi;
use App\Models\Valarpirai;
use App\Models\VilakuPalan;
use App\Models\VasthuNatkal;
use App\Models\VirathaThinam;
use App\Models\VirathamList;
use App\Models\WeekDay;
use App\Models\year;
use App\Models\yokam;
use App\Models\ThithiStarYogam;
use App\Models\DhaanaPalan;
use App\Models\ThumbalPalan;
use App\Models\ManayadiShasthiram;
use App\Models\DailyRaasiPalanList;
use App\Models\WeekRaasiPalanList;
use App\Models\MonthRaasiPalanList;
use App\Models\YearRaasiPalanList;
use App\Models\JothidaPathilkal;
use App\Models\KiragaPeyarchiPalangal;
use App\Models\NatchathiramThagavalkal;
use App\Models\RaasiThagavalkal;
use App\Models\VasthuThagavalkal;
use App\Models\NalSirapuGal;
use DateTime;

class ApiController extends Controller
{
    public function check(Request $request)
    {
        $key = $request->_key;
        if($key == 'ijklmn')
        {
            $value = 'poyVeWVAWoGXuKZxeCgQYlaYdOvUxCoQKczaj36T';
            return view('admin.api.api_route_list')->with('key', $value);
        }
        else
        {
            return view('admin.error');
        }
    }

    public function App(Request $request)
    {
        $resArr=[];
        $responseArr = array('result' => '0' ,'message'=>'failed','data'=> []);
        
        $app = AppList::Select('id','app_name')->where('deleted_at','0')->get();
        
        if(!empty($app) && count($app) > 0 )
        {
            foreach($app as $row)
            {   
                $temp['app_id'] = (string) $row->id ?? '';
                $temp['app_name'] = $row->app_name ?? '';
                array_push($resArr, $temp);
            }
            
            if(!empty($resArr)){
                $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
            }else{
                $responseArr = array('result'=>'0', 'data'=> $resArr, 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found',);
        }   
        return response()->json($responseArr);
    }

    public function language_list_api(Request $request)
    {
        $resArr=[];
        $responseArr = array('result' => '0' ,'message'=>'failed','data'=> []);
        
        $app = Category::Select('id','language_name','language_code')->where('deleted_at','0')->get();
        
        if(!empty($app) && count($app) > 0)
        {
            foreach($app as $row)
            {   
                $temp['language_id'] = (string) $row->id ?? '';
                $temp['language_name'] = $row->language_name ?? '';
                $temp['language_code'] = $row->language_code ?? '';
                array_push($resArr, $temp);
            }
            
            if(!empty($resArr)){
                $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
            }else{
                $responseArr = array('result'=>'0', 'data'=> $resArr, 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found',);
        }   
        return response()->json($responseArr);
    }

    public function get_calendar_image_list(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $date = $request->date;
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');

        $temp = [];
        if(!empty($date) && !empty($month) && !empty($year))
        {
            $month = (int) $month;
            $length = strlen($month);
            if($length == 1)
            {
                $month = str_replace($month,'0'.$month,$month);
            }
            $edit_category_id = CalendarImage::where('day',$date)->where('month',$month)->where('year',$year)->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            foreach($edit_category_id as $row)
            {
                $temp['calendar_id'] = (string) $row->id ?? '';
                $temp['calendar_date'] = $row->day ?? '';
                $temp['calendar_month'] = $row->month ?? '';
                $temp['calendar_year'] = $row->year ?? '';
                if($row->image_type == 1)
                {
                    if(!empty($row->image))
                    {
                        $temp['calendar_image'] = asset($row->image) ?? '';
                    }
                    else
                    { 
                        $temp['calendar_image'] = '';
                    }
                }
                else
                { 
                    $temp['calendar_image'] = $row->image ?? '';
                }
                $temp['date'] = $row->date;
                $temp['language_code'] = $row->langugae_code;
                $temp['language_id'] = $row->langugae_id;
                array_push($resArr, $temp);
            }
            
            if(!empty($resArr))
            {
                $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> $resArr, 'message'=>'No Data Found');
            }
            
        }
        else if(!empty($month) && !empty($year))
        {
            $month = (int) $month;
            $length = strlen($month);
            if($length == 1)
            {
                $month = str_replace($month,'0'.$month,$month);
            }           
            $edit_category_id = CalendarImage::where('month',$month)->where('year',$year)->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            foreach($edit_category_id as $row)
            {
                $temp['calendar_id'] = (string) $row->id ?? '';
                $temp['calendar_date'] = $row->day ?? '';
                $temp['calendar_month'] = $row->month ?? '';
                $temp['calendar_year'] = $row->year ?? '';
                if($row->image_type == 1)
                {
                    if(!empty($row->image))
                    {
                        $temp['calendar_image'] = asset($row->image) ?? '';
                    }
                    else
                    { 
                        $temp['calendar_image'] = '';
                    }
                }
                else
                { 
                    $temp['calendar_image'] = $row->image ?? '';
                }
                $temp['date'] = $row->date;
                $temp['language_code'] = $row->langugae_code;
                $temp['language_id'] = $row->langugae_id;
                array_push($resArr, $temp);
            }
            
            if(!empty($resArr))
            {
                $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> $resArr, 'message'=>'No Data Found');
            }
        }
        else if(!empty($year))
        {
            $edit = CalendarImage::where('year',$year)->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            foreach($edit as $row)
            {
                $temp['calendar_id'] = (string) $row->id ?? '';
                $temp['calendar_date'] = $row->day ?? '';
                $temp['calendar_month'] = $row->month ?? '';
                $temp['calendar_year'] = $row->year ?? '';
                if($row->image_type == 1)
                {
                    if(!empty($row->image))
                    {
                        $temp['calendar_image'] = asset($row->image) ?? '';
                    }
                    else
                    { 
                        $temp['calendar_image'] = '';
                    }
                }
                else
                { 
                    $temp['calendar_image'] = $row->image ?? '';
                }
                $temp['date'] = $row->date;
                $temp['language_code'] = $row->langugae_code;
                $temp['language_id'] = $row->langugae_id;
                array_push($resArr, $temp);
            }
            
            if(!empty($resArr))
            {
                $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> $resArr, 'message'=>'No Data Found');
            }

        }
        else
        {
            $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'Year Is Required');
        }
    
        return response()->json($responseArr);
    }

    public function get_daily_rasi_palan_list(Request $request)
    {
        $year = $request->year.'-01-01';
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($year))
        {
            $from = \Carbon\Carbon::parse($year)->startOfYear()->format('Y-m-d');
            $to = \Carbon\Carbon::parse($year)->endOfYear()->format('Y-m-d');
            $data = DailyRasiPalan::whereBetween('date', [$from, $to])->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            {
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp["date"] = (string) $row->date ?? '';
                    $temp["mesam"] = (string) $row->mesam ?? ''; 
                    $temp["risibam"] = (string) $row->risibam ?? '';
                    $temp["mithunam"] = (string) $row->mithunam ?? '';
                    $temp["kadakam"] = (string) $row->kadakam ?? '';
                    $temp["simmam"] = (string) $row->simmam ?? '';
                    $temp["kanni"] = (string) $row->kanni ?? '';
                    $temp["thulam"] = (string) $row->thulam ?? '';
                    $temp["viruchakam"] = (string) $row->viruchakam ?? '';
                    $temp["dhanusu"] = (string) $row->dhanusu ?? '';
                    $temp["makaram"] = (string) $row->makaram ?? '';
                    $temp["kumbam"] = (string) $row->kumbam ?? '';
                    $temp["meenam"] = (string) $row->meenam ?? '';
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);
    }

    public function get_festival_list(Request $request)
    {
        $year = $request->year.'-01-01';
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($year))
        {
            $from = \Carbon\Carbon::parse($year)->startOfYear()->format('Y-m-d');
            $to = \Carbon\Carbon::parse($year)->endOfYear()->format('Y-m-d');
            $data = FestivalList::whereBetween('date', [$from, $to])->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            {
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp['date'] = (string) $row->date ?? '';
                    $temp['quote'] = (string) $row->quote ?? '';
                    $temp['hindu_fes'] = (string) $row->hindu_fes ?? '';
                    $temp['muslim_fes'] = (string) $row->muslim_fes ?? '';
                    $temp['chirs_fes'] = (string) $row->chirs_fes ?? '';
                    $temp['gov_holiday'] = (string) $row->gov_holiday ?? '';
                    $temp['leave_flag'] = (string) $row->leave_flag ?? '';
                    $temp['imp_days'] = (string) $row->imp_days ?? '';
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }

    public function get_gowri_nalla_neram_list(Request $request)
    {

        $year = $request->year.'-01-01';
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($year))
        {
            $from = \Carbon\Carbon::parse($year)->startOfYear()->format('Y-m-d');
            $to = \Carbon\Carbon::parse($year)->endOfYear()->format('Y-m-d');
            $data = GowriNallaNeram::whereBetween('date', [$from, $to])->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            {
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp['date'] = (string) $row->date ?? '';
                    $temp['gowri_m'] = (string) $row->gowri_m ?? '';
                    $temp['gowri_e'] = (string) $row->gowri_e ?? '';
                    $temp['sooriya_r'] = (string) $row->sooriya_r ?? '';
                    $temp['karanam'] = (string) $row->karanam ?? '';
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }

    public function get_gowri_panjangam_neram_list(Request $request)
    {
        $year = $request->year;
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($year))
        {
            $data = GowriPanjangam::where('year',$year)->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            {
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp['year'] = (string) $row->year ?? '';
                    if(!empty($row->weekday))
                    {
                        $temp['weekday'] = (string) $row->week_day->days ?? '';
                    }
                    else
                    {
                        $temp['weekday'] =$row->weekday ?? '';
                    }
                    if($row->neram == 1)
                    {
                        $temp['neram'] = (string) "Morning" ?? '';
                    }
                    else
                    {
                        $temp['neram'] = (string) "Night" ?? '';
                    }
                    $temp['time'] = (string) $row->time ?? '';
                    
                    if(!empty($row->parikaram))
                    {
                        $temp['parikaram'] = (string) $row->Parigaram->parigaram_name ?? '';
                    }
                    else
                    {
                        $temp['parikaram'] = $row->parikaram ?? '';
                    }
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }

    public function important_days_list_api(Request $request)
    {
        $resArr=[];
        $responseArr = array('result' => '0' ,'message'=>'failed','data'=> []);
        
        $app = ImportantDays::where('deleted_at','0')->get();
        $temp = [];
        if(!empty($app) && count($app) > 0 )
        {
            foreach($app as $row)
            {   
                $temp['date'] = (string) $row->date ?? '';
                $temp['month'] = (string) $row->month ?? '';
                $temp['imp_day'] = (string) $row->imp_day ?? '';
                $temp['birthday'] = (string) $row->birthday ?? '';
                $temp['death'] = (string) $row->death ?? '';
                $temp['language_id'] = $row->langugae_id ?? '';
                $temp['language_code'] = $row->langugae_code ?? '';
                array_push($resArr, $temp);
            }
            
            if(!empty($resArr)){
                $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
            }else{
                $responseArr = array('result'=>'0', 'data'=> $resArr, 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found',);
        }   
        return response()->json($responseArr);
    }

    public function get_kaka_palan_list(Request $request)
    {
        $app_id = $request->app_id;
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($app_id))
        {
            $data = KakaPalan::where('app_id',$app_id)->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            {
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp['title'] = (string) $row->title ?? '';
                    if(!empty($row->content))
                    {
                        $temp['content'] = (string) $row->content ?? '';
                    }
                    else
                    {
                        $temp['content'] =$row->content ?? '';
                    }
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }

    public function get_kavanupalangal_category_list_api(Request $request)
    {   
        $resArr=[];
        $responseArr = array('result' => '0' ,'message'=>'failed','data'=> []);
        
        $app = kanavupalangalCategory::where('deleted_at','0')->where('is_active',0)->get();
        $temp = [];
        if(!empty($app) && count($app) > 0)
        {
            foreach($app as $row)
            {   
                $temp['category_id'] = (string) $row->id ?? '';
                $temp['category_name'] = (string) $row->category_name ?? '';
                $temp['language_code'] = $row->langugae_code;
                $temp['language_id'] = $row->langugae_id;
                array_push($resArr, $temp);
            }
            
            if(!empty($resArr)){
                $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
            }
            else{
                $responseArr = array('result'=>'0', 'data'=> $resArr, 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found',);
        }   
        return response()->json($responseArr);
    }

    public function get_kanavu_palangal_list(Request $request)
    {
        $category_id = $request->category_id;
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($category_id))
        {
            $data = kanavuPalangal::where('category_id',$category_id)->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            {
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp['category'] = (string) $row->category ?? '';
                    if(!empty($row->palan))
                    {
                        $temp['palan'] = (string) $row->palan ?? '';
                    }
                    else
                    {
                        $temp['palan'] =$row->palan ?? '';
                    }
                    $temp['category_id'] = (string) $row->category_id ?? '';
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }

    public function get_kiraga_orai_kallin_kalam_list(Request $request)
    {
        $year = $request->year;
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($year))
        {
            $data = KiragaOraikalinKalam::where('year',$year)->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            {
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp['year'] = (string) $row->year ?? '';
                    if(!empty($row->weekday))
                    {
                        $temp['weekday'] = (string) $row->week_day->days ?? '';
                    }
                    else
                    {
                        $temp['weekday'] =$row->weekday ?? '';
                    }
                    if($row->neram == 1)
                    {
                        $temp['neram'] = (string) "Morning" ?? '';
                    }
                    else
                    {
                        $temp['neram'] = (string) "Night" ?? '';
                    }
                    $temp['time'] = (string) $row->time ?? '';
                    
                    if(!empty($row->yokam))
                    {
                        $temp['yokam'] = (string) $row->yogams->yokam ?? '';
                    }
                    else
                    {
                        $temp['yokam'] = $row->yokam ?? '';
                    }
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);
    }

    public function get_kocharam_list(Request $request)
    {
        $year = $request->year.'-01-01';
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($year))
        {
            $from = \Carbon\Carbon::parse($year)->startOfYear()->format('Y-m-d');
            $to = \Carbon\Carbon::parse($year)->endOfYear()->format('Y-m-d');
            $data = Kocharam::whereBetween('date', [$from, $to])->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            { 
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp["date"] = (string) $row->date ?? '';
                    $temp["c1"] = (string) $row->c1 ?? '';
                    $temp["c2"] = (string) $row->c2 ?? '';
                    $temp["c3"] = (string) $row->c3 ?? '';
                    $temp["c4"] = (string) $row->c4 ?? '';
                    $temp["c5"] = (string) $row->c5 ?? '';
                    $temp["c6"] = (string) $row->c6 ?? '';
                    $temp["c7"] = (string) $row->c7 ?? '';
                    $temp["c8"] = (string) $row->c8 ?? '';
                    $temp["c9"] = (string) $row->c9 ?? '';
                    $temp["c10"] = (string) $row->c10 ?? '';
                    $temp["c11"] = (string) $row->c11 ?? '';
                    $temp["c12"] = (string) $row->c12 ?? '';
                    $temp["m1"] = (string) $row->m1 ?? '';
                    $temp["m2"] = (string) $row->m2 ?? '';
                    $temp["m3"] = (string) $row->m3 ?? '';
                    $temp["m4"] = (string) $row->m4 ?? '';
                    $temp["m5"] = (string) $row->m5 ?? '';
                    $temp["m6"] = (string) $row->m6 ?? '';
                    $temp["m7"] = (string) $row->m7 ?? '';
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }

    public function get_macha_palangals_list(Request $request)
    {
        $app_id = $request->app_id;
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($app_id))
        {
            $data = MachaPalangal::where('app_id',$app_id)->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            {
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp['title'] = (string) $row->title ?? '';
                    if(!empty($row->pakam))
                    {
                        $temp['pakam'] = (string) $row->body->body_parts ?? '';
                    }
                    else
                    {
                        $temp['pakam'] =$row->pakam ?? '';
                    }
                    if(!empty($row->content))
                    {
                        $temp['content'] = (string) $row->content ?? '';
                    }
                    else
                    {
                        $temp['content'] =$row->content ?? '';
                    }
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }

    public function get_moogurthams_list(Request $request)
    {
        $year = $request->year.'-01-01';
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($year))
        {
            $from = \Carbon\Carbon::parse($year)->startOfYear()->format('Y-m-d');
            $to = \Carbon\Carbon::parse($year)->endOfYear()->format('Y-m-d');
            $data = Moogurtham::whereBetween('date', [$from, $to])->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            { 
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp["date"] = (string) $row->date ?? '';
                    if(!empty($row->thethi))
                    {
                        $temp["thethi"] = (string) $row->thethi_list->thithi_name ?? '';
                    }
                    else
                    {
                        $temp["thethi"] = (string) $row->thethi ?? '';
                    }
                    
                    if(!empty($row->star))
                    {
                        $temp["star"] = (string) $row->stars_list->star_name ?? '';
                    }
                    else
                    {
                        $temp["star"] = (string) $row->star ?? '';
                    }
                    if(!empty($row->yokam))
                    {
                        $temp["yokam"] = (string) $row->yogams->yokam ?? '';
                    }
                    else
                    {
                        $temp["yokam"] = (string) $row->yokam ?? '';
                    }
                    if(!empty($row->lakknam))
                    {
                        $temp["lakknam"] = (string) $row->Laknam_list->laknam_name ?? '';
                    }
                    else
                    {
                        $temp["lakknam"] = (string) $row->laknam_name ?? '';
                    }
                    $temp["time"] = (string) $row->time ?? '';
                    $temp["valrpirai"] = (string) $row->valrpirai ?? '';

                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }

    public function get_natchathiral_natkals_list(Request $request)
    {
        $year = $request->year.'-01-01';
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($year))
        {
            $from = \Carbon\Carbon::parse($year)->startOfYear()->format('Y-m-d');
            $to = \Carbon\Carbon::parse($year)->endOfYear()->format('Y-m-d');
            $data = NatchathiralNatkal::whereBetween('date', [$from, $to])->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            { 
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp["date"] = (string) $row->date ?? '';
                    $temp["Time1"] = (string) $row->Time1 ?? '';
                    $temp["Time2"] = (string) $row->Time2 ?? '';
                    if(!empty($row->Nat1) && (($row->Nat1 != "-") == true))
                    {
                        $temp["Nat1"] = (string) $row->stars_list_1->star_name ?? '';
                    }
                    else
                    {
                        $temp["Nat1"] = (string) $row->Nat1 ?? '';
                    }
                    
                    if(!empty($row->Nat2) && (($row->Nat2 != "-") == true))
                    {
                        $temp["Nat2"] = (string) $row->stars_list_2->star_name ?? '';
                    }
                    else
                    {
                        $temp["Nat2"] = (string) $row->Nat2 ?? '';
                    }

                    if(!empty($row->Nat3) && (($row->Nat3 != "-") == true))
                    {
                        $temp["Nat3"] = (string) $row->stars_list_3->star_name ?? $row->Nat3  ;
                    }
                    else
                    {
                        $temp["Nat3"] = (string) $row->Nat3 ?? '';
                    }

                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }

    public function get_palli_sollum_palans_list_api(Request $request)
    {   
        $resArr=[];
        $responseArr = array('result' => '0' ,'message'=>'failed','data'=> []);
        
        $app = PalliSollumPalan::where('deleted_at','0')->where('is_active',0)->where('deleted_at','0')->get();
        $temp = [];
        if(!empty($app) && count($app) > 0)
        {
            foreach($app as $row)
            {   
                $temp['id'] = (string) $row->id ?? '';
                if(!empty($row->day))
                {
                    $temp["day"] = (string) $row->week_day->days ?? '';
                }
                else
                {
                    $temp["day"] = (string) $row->day ?? '';
                }
                if(!empty($row->palan))
                {
                    $temp["palan"] = (string) $row->palan ?? '';
                }
                else
                {
                    $temp["palan"] = (string) $row->palan ?? '';
                }
                $temp['language_code'] = $row->langugae_code;
                $temp['language_id'] = $row->langugae_id;
                array_push($resArr, $temp);
            }
            
            if(!empty($resArr)){
                $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
            }
            else{
                $responseArr = array('result'=>'0', 'data'=> $resArr, 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found',);
        }   
        return response()->json($responseArr);
    }

    public function get_palli_villum_palans_list_api(Request $request)
    {   
        $resArr=[];
        $responseArr = array('result' => '0' ,'message'=>'failed','data'=> []);
        
        $app = PalliVillumPalan::where('deleted_at','0')->where('is_active',0)->get();
        $temp = [];
        if(!empty($app) && count($app) > 0)
        {
            foreach($app as $row)
            {   
                $temp['id'] = (string) $row->id ?? '';
                if(!empty($row->pakam))
                {
                    $temp["pakam"] = (string) $row->body->body_parts ?? '';
                }
                else
                {
                    $temp["pakam"] = (string) $row->day ?? '';
                }
                $temp['left'] = $row->left;
                $temp['right'] = $row->right;
                $temp['language_code'] = $row->langugae_code;
                $temp['language_id'] = $row->langugae_id;
                array_push($resArr, $temp);
            }
            
            if(!empty($resArr)){
                $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
            }
            else{
                $responseArr = array('result'=>'0', 'data'=> $resArr, 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found',);
        }   
        return response()->json($responseArr);
    }

    public function get_raagu_kuligai_emagandams_list(Request $request)
    {
        $year = $request->year;
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($year))
        {
            $data = RaaguKuligaiEmagandam::where('year',$year)->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            {
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp['year'] = (string) $row->year ?? '';
                    if(!empty($row->weekday))
                    {
                        $temp['weekday'] = (string) $row->week_day->days ?? '';
                    }
                    else
                    {
                        $temp['weekday'] =$row->weekday ?? '';
                    } 
                    $temp['ragu'] = (string) $row->ragu ?? '';
                    $temp['kuligai'] = (string) $row->kuligai ?? '';
                    $temp['emakandam'] = (string) $row->emakandam ?? '';
                    if(!empty($row->soolam))
                    {
                        $temp['soolam'] = (string) $row->thisai_list->thisai_name ?? '';
                    }
                    else
                    {
                        $temp['soolam'] = $row->soolam ?? '';
                    }

                    if(!empty($row->parikaram))
                    {
                        $temp['parikaram'] = (string) $row->Parigaram->parigaram_name ?? '';
                    }
                    else
                    {
                        $temp['parikaram'] = $row->parikaram ?? '';
                    }
                    
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);
    }

    public function get_vasthu_natkals_list(Request $request)
    {
        $year = $request->year;
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($year))
        {
            $data = VasthuNatkal::where('year',$year)->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            {
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp['year'] = (string) $row->year ?? '';
                    $temp['day'] = (string) $row->day ?? '';
                    $temp['neram'] = (string) $row->neram ?? '';
                    $temp['time'] = (string) $row->time ?? '';
                    
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);
    }

    public function get_vilaku_palans_list(Request $request)
    {
        $app_id = $request->app_id;
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($app_id))
        {
            $data = VilakuPalan::where('app_id',$app_id)->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            {
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp['title'] = (string) $row->title ?? '';
                    if(!empty($row->content))
                    {
                        $temp['content'] = (string) $row->content ?? '';
                    }
                    else
                    {
                        $temp['content'] =$row->content ?? '';
                    }
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }

    public function get_viratha_thinams_list(Request $request)
    {
        $year = $request->year.'-01-01';
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($year))
        {
            $from = \Carbon\Carbon::parse($year)->startOfYear()->format('Y-m-d');
            $to = \Carbon\Carbon::parse($year)->endOfYear()->format('Y-m-d');
            $data = VirathaThinam::whereBetween('date', [$from, $to])->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            { 
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp["date"] = (string) $row->date ?? '';
                    if(!empty($row->viratham))
                    {
                        $temp["viratham"] = (string) $row->viratham_lists->viratha_thinam ?? '';
                    }
                    else
                    {
                        $temp["viratham"] = (string) $row->viratham ?? '';
                    }
                    
                    $temp["time"] = (string) $row->time ?? '';
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }


    public function get_thiti_star_yokam_list(Request $request)
    {
        $year = $request->year.'-01-01';
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($year))
        {
            $from = \Carbon\Carbon::parse($year)->startOfYear()->format('Y-m-d');
            $to = \Carbon\Carbon::parse($year)->endOfYear()->format('Y-m-d');
            $data = ThithiStarYogam::whereBetween('date', [$from, $to])->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            { 
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp["date"] = (string) $row->date ?? '';
                    $temp["day_type"] = (string) $row->day_type ?? '';
                    $temp["thiti"] = (string) $row->thiti ?? '';
                    $temp["yokam"] = (string) $row->yokam ?? '';
                    $temp["chanthran"] = (string) $row->chandiran_name ?? '';
                    $temp["pirai"] = (string) $row->pirai ?? '';
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);
    }


    public function get_englmonth_to_tamilmonth_list(Request $request)
    {
        $year = $request->year.'-01-01';
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($year))
        {
            $from = \Carbon\Carbon::parse($year)->startOfYear()->format('Y-m-d');
            $to = \Carbon\Carbon::parse($year)->endOfYear()->format('Y-m-d');
            $data = EnglMonthToTamilMonth::whereBetween('date', [$from, $to])->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            { 
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp["date"] = (string) $row->date ?? '';
                    $temp["day"] = (string) $row->day ?? '';
                    $temp["month"] = (string) $row->month ?? '';
                    $temp["year"] = (string) $row->year ?? '';
                    $temp["weekday"] = (string) $row->week_day->days ?? '';
                    $temp["tam_month"] = (string) $row->tam_month ?? '';
                    $temp["tam_day"] = (string) $row->tam_day ?? '';
                    $temp["tam_year"] = (string) $row->tam_year ?? '';
                    $temp["tam_year_name"] = (string) $row->tam_year_name ?? '';
                    $temp["nallanerem_m"] = (string) $row->nallanerem_m ?? '';
                    $temp["nallanerem_e"] = (string) $row->nallanerem_e ?? '';
                    $temp["laknam"] = (string) $row->laknam_list->laknam_name ?? '';
                    $temp["laknam_time"] = (string) $row->laknam_time ?? '';
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);
    }

    public function get_dhaana_palan_list(Request $request)
    {
        $app_id = $request->app_id;
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($app_id))
        {
            $data = DhaanaPalan::where('app_id',$app_id)->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            {
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp['title'] = (string) $row->title ?? '';
                    if(!empty($row->content))
                    {
                        $temp['content'] = (string) $row->content ?? '';
                    }
                    else
                    {
                        $temp['content'] =$row->content ?? '';
                    }
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }

    public function get_manayadi_shasthiram_list(Request $request)
    {
        $app_id = $request->app_id;
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($app_id))
        {
            $data = ManayadiShasthiram::where('app_id',$app_id)->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            {
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp['title'] = (string) $row->title ?? '';
                    if(!empty($row->content))
                    {
                        $temp['content'] = (string) $row->content ?? '';
                    }
                    else
                    {
                        $temp['content'] =$row->content ?? '';
                    }
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }

    public function get_thumbal_palans_list(Request $request)
    {
        $app_id = $request->app_id;
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($app_id))
        {
            $data = ThumbalPalan::where('app_id',$app_id)->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            {
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp['title'] = (string) $row->title ?? '';
                    if(!empty($row->content))
                    {
                        $temp['content'] = (string) $row->content ?? '';
                    }
                    else
                    {
                        $temp['content'] =$row->content ?? '';
                    }
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }

    public function laknam_list_api(Request $request)
    {
        $resArr=[];
        $responseArr = array('result' => '0' ,'message'=>'failed','data'=> []);
        
        $app = Laknam::Select('id','laknam_name')->where('deleted_at','0')->get();
        
        if(!empty($app) && count($app) > 0)
        {
            foreach($app as $row)
            {   
                $temp['laknam_id'] = (string) $row->id ?? '';
                $temp['laknam_name'] = $row->laknam_name ?? '';
                array_push($resArr, $temp);
            }
            
            if(!empty($resArr)){
                $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
            }else{
                $responseArr = array('result'=>'0', 'data'=> $resArr, 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found',);
        }   
        return response()->json($responseArr);
    }

    public function get_week_raasi_palan_list(Request $request)
    {
        $year = $request->year;
        $week = $request->week;
        $lakknam = $request->lakknam;
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($year) && !empty($week) && !empty($lakknam))
        {
            if($week <= 52)
            {
                $data = WeekRaasiPalanList::where('langugae_code',$language_code)->where('year',$year)->where('week',$week)->where('lakknam',$lakknam)->where('deleted_at','0')->get();
                if(!empty($data) && count($data) > 0)
                {
                    foreach($data as $row)
                    {
                        $temp["id"] = (string) $row->id ?? '';
                        $temp['year'] = (string) $row->year ?? '';
                        $temp['week'] = (string) $row->week ?? '';
                        
                        $end_of_the_week = $row['end_of_the_week'];
                        $stamp = strtotime($end_of_the_week);
                        $end_of_the_week_1 = $stamp*1000;
                        $temp['end_of_the_week'] = $end_of_the_week_1 ?? '';

                        
                        $start_of_the_week = $row['start_of_the_week'];
                        $stamp = strtotime($start_of_the_week);
                        $start_of_the_week_1 = $stamp*1000;
                        $temp['start_of_the_week'] = $start_of_the_week_1 ?? '';
                        
                        $temp['lakknam'] = (string) $row->lakknam ?? '';
                        $temp['lakknam_name'] = (string) $row->Laknam_list->laknam_name ?? '';
                        if(!empty($row->content))
                        {
                            $temp['content'] = (string) $row->content ?? '';
                        }
                        else
                        {
                            $temp['content'] =$row->content ?? '';
                        }
                        $temp['language_code'] = $row->langugae_code;
                        $temp['language_id'] = $row->langugae_id;
                        array_push($resArr, $temp);
                    }
                    
                    if(!empty($resArr))
                    {
                        $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                    }
                    else
                    {
                        $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                    }
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }

    public function get_month_raasi_palan_list(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $lakknam = $request->lakknam;
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($year) && !empty($month) && !empty($lakknam))
        {
            if($month <= 12)
            {
                $data = MonthRaasiPalanList::where('langugae_code',$language_code)->where('year',$year)->where('month',$month)->where('lakknam',$lakknam)->where('deleted_at','0')->get();
                if(!empty($data) && count($data) > 0)
                {
                    foreach($data as $row)
                    {
                        $temp["id"] = (string) $row->id ?? '';
                        $temp['year'] = (string) $row->year ?? '';
                        $temp['month'] = (string) $row->month ?? '';
                        
                        $end_of_the_month = $row['end_of_the_month'];
                        $stamp = strtotime($end_of_the_month);
                        $end_of_the_month_1 = $stamp*1000;
                        $temp['end_of_the_month'] = $end_of_the_month_1 ?? '';

                        
                        $start_of_the_month = $row['start_of_the_month'];
                        $stamp = strtotime($start_of_the_month);
                        $start_of_the_month_1 = $stamp*1000;
                        $temp['start_of_the_month'] = $start_of_the_month_1 ?? '';
                        
                        $temp['lakknam'] = (string) $row->lakknam ?? '';
                        $temp['lakknam_name'] = (string) $row->Laknam_list->laknam_name ?? '';
                        if(!empty($row->content))
                        {
                            $temp['content'] = (string) $row->content ?? '';
                        }
                        else
                        {
                            $temp['content'] =$row->content ?? '';
                        }
                        $temp['language_code'] = $row->langugae_code;
                        $temp['language_id'] = $row->langugae_id;
                        array_push($resArr, $temp);
                    }
                    
                    if(!empty($resArr))
                    {
                        $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                    }
                    else
                    {
                        $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                    }
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }
    
    public function get_year_raasi_palan_list(Request $request)
    {
        $year = $request->year;
        $lakknam = $request->lakknam;
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($year) && !empty($lakknam))
        {
            $data = YearRaasiPalanList::where('langugae_code',$language_code)->where('year',$year)->where('lakknam',$lakknam)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            {
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp['year'] = (string) $row->year ?? '';
                    
                    $end_of_the_year = $row['end_of_the_year'];
                    $stamp = strtotime($end_of_the_year);
                    $end_of_the_year_1 = $stamp*1000;
                    $temp['end_of_the_year'] = $end_of_the_year_1 ?? '';

                    
                    $start_of_the_year = $row['start_of_the_year'];
                    $stamp = strtotime($start_of_the_year);
                    $start_of_the_year_1 = $stamp*1000;
                    $temp['start_of_the_year'] = $start_of_the_year_1 ?? '';
                    
                    $temp['lakknam'] = (string) $row->lakknam ?? '';
                    $temp['lakknam_name'] = (string) $row->Laknam_list->laknam_name ?? '';
                    if(!empty($row->content))
                    {
                        $temp['content'] = (string) $row->content ?? '';
                    }
                    else
                    {
                        $temp['content'] =$row->content ?? '';
                    }
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }
    
    public function get_jothida_pathilgal_list(Request $request)
    {
        $app_id = $request->app_id;
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($app_id))
        {
            $data = JothidaPathilkal::where('app_id',$app_id)->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            {
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp['title'] = (string) $row->title ?? '';
                    if(!empty($row->content))
                    {
                        $temp['content'] = (string) $row->content ?? '';
                    }
                    else
                    {
                        $temp['content'] =$row->content ?? '';
                    }
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }

    
    public function get_vasathu_thagavalkal_list(Request $request)
    {
        $app_id = $request->app_id;
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($app_id))
        {
            $data = VasthuThagavalkal::where('app_id',$app_id)->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            {
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp['title'] = (string) $row->title ?? '';
                    if(!empty($row->content))
                    {
                        $temp['content'] = (string) $row->content ?? '';
                    }
                    else
                    {
                        $temp['content'] =$row->content ?? '';
                    }
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }
    
    public function get_natchathiram_thagavalkal_list(Request $request)
    {
        $app_id = $request->app_id;
        $language_code = $request->language_code;
        $star = $request->star;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($app_id) && !empty($star))
        {
            $data = NatchathiramThagavalkal::where('app_id',$app_id)->where('stars_id',$star)->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            {
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp['title'] = (string) $row->title ?? '';
                    $temp['stars_id'] = (string) $row->stars_id ?? '';
                    $temp['stars_name'] = (string) $row->stars_name ?? '';
                    if(!empty($row->content))
                    {
                        $temp['content'] = (string) $row->content ?? '';
                    }
                    else
                    {
                        $temp['content'] =$row->content ?? '';
                    }
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }
    
    public function get_raasi_thagavalkal_list(Request $request)
    {
        $app_id = $request->app_id;
        $language_code = $request->language_code;
        $lakknam = $request->lakknam;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($app_id) && !empty($lakknam))
        {
            $data = RaasiThagavalkal::where('app_id',$app_id)->where('lakknam',$lakknam)->where('langugae_code',$language_code)->where('deleted_at','0')->get();
            if(!empty($data) && count($data) > 0)
            {
                foreach($data as $row)
                {
                    $temp["id"] = (string) $row->id ?? '';
                    $temp['title'] = (string) $row->title ?? '';
                    $temp['lakknam'] = (string) $row->lakknam ?? '';
                    $temp['lakknam_name'] = (string) $row->laknam_list->laknam_name ?? '';
                    if(!empty($row->content))
                    {
                        $temp['content'] = (string) $row->content ?? '';
                    }
                    else
                    {
                        $temp['content'] =$row->content ?? '';
                    }
                    $temp['language_code'] = $row->langugae_code;
                    $temp['language_id'] = $row->langugae_id;
                    array_push($resArr, $temp);
                }
                
                if(!empty($resArr))
                {
                    $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }
    
    public function get_kiraga_peyarchi_palan_list(Request $request)
    {
        $flag_of_kiraga_peyarchi = $request->flag_of_kiraga_peyarchi;
        $language_code = $request->language_code;
        $lakknam = $request->lakknam;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($flag_of_kiraga_peyarchi) && !empty($lakknam))
        {
            if($flag_of_kiraga_peyarchi <= 3)
            {
                $data = KiragaPeyarchiPalangal::where('flag_of_kiraga_peyarchi',$flag_of_kiraga_peyarchi)->where('lakknam',$lakknam)->where('langugae_code',$language_code)->where('deleted_at','0')->get();
                if(!empty($data) && count($data) > 0)
                {
                    foreach($data as $row)
                    {
                        $temp["id"] = (string) $row->id ?? '';
                        $temp['lakknam'] = (string) $row->lakknam ?? '';
                        $temp['lakknam_name'] = (string) $row->laknam_list->laknam_name ?? '';
                        if(!empty($row->content))
                        {
                            $temp['content'] = (string) $row->content ?? '';
                        }
                        else
                        {
                            $temp['content'] =$row->content ?? '';
                        }
                        $temp['flag_of_kiraga_peyarchi'] = $row->flag_of_kiraga_peyarchi ?? '';
                        $temp['language_code'] = $row->langugae_code;
                        $temp['language_id'] = $row->langugae_id;
                        array_push($resArr, $temp);
                    }
                    
                    if(!empty($resArr))
                    {
                        $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                    }
                    else
                    {
                        $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                    }
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'Flag Maximum Reach 3');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }

    
    public function stars_list_api(Request $request)
    {
        $resArr=[];
        $responseArr = array('result' => '0' ,'message'=>'failed','data'=> []);
        
        $app = Stars::Select('id','star_name')->where('deleted_at','0')->get();
        
        if(!empty($app) && count($app) > 0)
        {
            foreach($app as $row)
            {   
                $temp['stars_id'] = (string) $row->id ?? '';
                $temp['star_name'] = $row->star_name ?? '';
                array_push($resArr, $temp);
            }
            
            if(!empty($resArr)){
                $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
            }else{
                $responseArr = array('result'=>'0', 'data'=> $resArr, 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found',);
        }   
        return response()->json($responseArr);
    }

    public function get_dhinasari_raasi_palan_list(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $date = $request->date;
        $lakknam = $request->lakknam;
        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];
        if(!empty($year) && !empty($month) && !empty($date) && !empty($lakknam))
        {
            if($month <= 12 && $date <= 31)
            {
                $month = (int) $month;
                $length = strlen($month);
                if($length == 1)
                {
                    $month = str_replace($month,'0'.$month,$month);
                }
                
                $date = (int) $date;
                $length_1 = strlen($date);
                if($length_1 == 1)
                {
                    $date = str_replace($date,'0'.$date,$date);
                }

                $date_check_api = $year.'-'.$month.'-'.$date;
                $data = DailyRaasiPalanList::where('langugae_code',$language_code)->where('year',$year)->where('date',$date_check_api)->where('lakknam',$lakknam)->where('deleted_at','0')->get();
                if(!empty($data) && count($data) > 0)
                {
                    foreach($data as $row)
                    {
                        $temp["id"] = (string) $row->id ?? '';
                        $temp['year'] = (string) $row->year ?? '';
                        
                        $date_1 = $row['date'];
                        $stamp = strtotime($date_1);
                        $date_1_1 = $stamp*1000;
                        $temp['date'] = $date_1_1 ?? '';
                        
                        $temp['lakknam'] = (string) $row->lakknam ?? '';
                        $temp['lakknam_name'] = (string) $row->Laknam_list->laknam_name ?? '';
                        if(!empty($row->content))
                        {
                            $temp['content'] = (string) $row->content ?? '';
                        }
                        else
                        {
                            $temp['content'] =$row->content ?? '';
                        }
                        $temp['language_code'] = $row->langugae_code;
                        $temp['language_id'] = $row->langugae_id;
                        array_push($resArr, $temp);
                    }
                    
                    if(!empty($resArr))
                    {
                        $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                    }
                    else
                    {
                        $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                    }
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }
            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);

    }

    public function get_nal_sirapugal_list(Request $request)
    {
        $utcTime = $request->date; // UTC time
        $localTime =  date('Y-m-d', $utcTime);
        
        $tomorrow = \Carbon\Carbon::parse($localTime)->addDay()->toDateString();
        $date = new DateTime($localTime);
        $week = $date->format("W");
        $month = $date->format("m");

        $type = $request->flag_of_nal_sirapugal;

        $language_code = $request->language_code;
        $resArr = [];
        $responseArr = array('result' => '0' ,'data'=> [] ,'message'=>'No Data Found');
        $temp = [];

        if($type == 1)
        {
            if(!empty($type) && !empty($localTime))
            {
                $data = NalSirapuGal::where('langugae_code',$language_code)->where('today_date',$localTime)->where('deleted_at','0')->get();
                if(!empty($data) && count($data) > 0)
                {
                    foreach($data as $row)
                    {
                        $temp["id"] = (string) $row->id ?? '';
                        
                        
                        $currentTimestamp = \Carbon\Carbon::parse($row['today_date'])->timestamp;

                        $temp['date'] = $currentTimestamp ?? '';
                        $temp['week'] = (string) $row->week ?? '';
                        $temp['month'] = (string) $row->month ?? '';
                        
                        if(!empty($row->thithi))
                        {
                            $temp['thithi'] = (string) $row->thithi ?? '';
                        }
                        else
                        {
                            $temp['thithi'] =$row->thithi ?? '';
                        }

                        $temp['natchathiram'] = (string) $row->natchathiram ?? '';
                        $temp['amiratha_yogam'] = (string) $row->amiratha_yogam ?? '';
                        $temp['santhirastam_nat'] = (string) $row->santhirastam_nat ?? '';
                        $temp['pandigai'] = (string) $row->pandigai ?? '';
                        $temp['valipaadu'] = (string) $row->valipaadu ?? '';
                        $temp['yetharkellam_sirappu'] = (string) $row->yetharkellam_sirappu ?? '';
                        $temp['lakknam'] = json_decode($row->lakknam) ?? '';

                        $temp['language_code'] = $row->langugae_code;
                        $temp['language_id'] = $row->langugae_id;
                        array_push($resArr, $temp);
                    }
                    
                    if(!empty($resArr))
                    {
                        $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                    }
                    else
                    {
                        $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                    }
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }

            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else if($type == 2)
        {
            if(!empty($type) && !empty($tomorrow))
            {
                $data = NalSirapuGal::where('langugae_code',$language_code)->where('today_date',$tomorrow)->where('deleted_at','0')->get();
                if(!empty($data) && count($data) > 0)
                {
                    foreach($data as $row)
                    {
                        $temp["id"] = (string) $row->id ?? '';
                        $date_1 = $row['today_date'];
                        $stamp = strtotime($date_1);
                        $date_1_1 = $stamp*1000;
                        $temp['date'] = $date_1_1 ?? '';
                        $temp['week'] = (string) $row->week ?? '';
                        $temp['month'] = (string) $row->month ?? '';
                        
                        if(!empty($row->thithi))
                        {
                            $temp['thithi'] = (string) $row->thithi ?? '';
                        }
                        else
                        {
                            $temp['thithi'] =$row->thithi ?? '';
                        }

                        $temp['natchathiram'] = (string) $row->natchathiram ?? '';
                        $temp['amiratha_yogam'] = (string) $row->amiratha_yogam ?? '';
                        $temp['santhirastam_nat'] = (string) $row->santhirastam_nat ?? '';
                        $temp['pandigai'] = (string) $row->pandigai ?? '';
                        $temp['valipaadu'] = (string) $row->valipaadu ?? '';
                        $temp['yetharkellam_sirappu'] = (string) $row->yetharkellam_sirappu ?? '';
                        $temp['lakknam'] = json_decode($row->lakknam) ?? '';

                        $temp['language_code'] = $row->langugae_code;
                        $temp['language_id'] = $row->langugae_id;
                        array_push($resArr, $temp);
                    }
                    
                    if(!empty($resArr))
                    {
                        $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                    }
                    else
                    {
                        $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                    }
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }

            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else if($type == 3)
        {
            if(!empty($type) && !empty($week))
            {
                $data = NalSirapuGal::where('langugae_code',$language_code)->where('week',(int) $week)->where('deleted_at','0')->get();
                if(!empty($data) && count($data) > 0)
                {
                    foreach($data as $row)
                    {
                        $temp["id"] = (string) $row->id ?? '';
                        $date_1 = $row['today_date'];
                        $stamp = strtotime($date_1);
                        $date_1_1 = $stamp*1000;
                        $temp['date'] = $date_1_1 ?? '';
                        $temp['week'] = (string) $row->week ?? '';
                        $temp['month'] = (string) $row->month ?? '';
                        
                        if(!empty($row->thithi))
                        {
                            $temp['thithi'] = (string) $row->thithi ?? '';
                        }
                        else
                        {
                            $temp['thithi'] =$row->thithi ?? '';
                        }

                        $temp['natchathiram'] = (string) $row->natchathiram ?? '';
                        $temp['amiratha_yogam'] = (string) $row->amiratha_yogam ?? '';
                        $temp['santhirastam_nat'] = (string) $row->santhirastam_nat ?? '';
                        $temp['pandigai'] = (string) $row->pandigai ?? '';
                        $temp['valipaadu'] = (string) $row->valipaadu ?? '';
                        $temp['yetharkellam_sirappu'] = (string) $row->yetharkellam_sirappu ?? '';
                        $temp['lakknam'] = json_decode($row->lakknam) ?? '';

                        $temp['language_code'] = $row->langugae_code;
                        $temp['language_id'] = $row->langugae_id;
                        array_push($resArr, $temp);
                    }
                    
                    if(!empty($resArr))
                    {
                        $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                    }
                    else
                    {
                        $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                    }
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }

            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else if($type == 4)
        {
            if(!empty($type) && !empty($month))
            {
                $data = NalSirapuGal::orderBy('today_date','asc')->where('langugae_code',$language_code)->where('month',(int) $month)->where('deleted_at','0')->get();
                if(!empty($data) && count($data) > 0)
                {
                    foreach($data as $row)
                    {
                        $temp["id"] = (string) $row->id ?? '';
                        $date_1 = $row['today_date'];
                        $stamp = strtotime($date_1);
                        $date_1_1 = $stamp*1000;
                        $temp['date'] = $date_1_1 ?? '';
                        $temp['week'] = (string) $row->week ?? '';
                        $temp['month'] = (string) $row->month ?? '';
                        
                        if(!empty($row->thithi))
                        {
                            $temp['thithi'] = (string) $row->thithi ?? '';
                        }
                        else
                        {
                            $temp['thithi'] =$row->thithi ?? '';
                        }

                        $temp['natchathiram'] = (string) $row->natchathiram ?? '';
                        $temp['amiratha_yogam'] = (string) $row->amiratha_yogam ?? '';
                        $temp['santhirastam_nat'] = (string) $row->santhirastam_nat ?? '';
                        $temp['pandigai'] = (string) $row->pandigai ?? '';
                        $temp['valipaadu'] = (string) $row->valipaadu ?? '';
                        $temp['yetharkellam_sirappu'] = (string) $row->yetharkellam_sirappu ?? '';
                        $temp['lakknam'] = json_decode($row->lakknam) ?? '';

                        $temp['language_code'] = $row->langugae_code;
                        $temp['language_id'] = $row->langugae_id;
                        array_push($resArr, $temp);
                    }
                    
                    if(!empty($resArr))
                    {
                        $responseArr = array('result'=>'1', 'data'=> $resArr, 'message'=>'Success');
                    }
                    else
                    {
                        $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                    }
                }
                else
                {
                    $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
                }

            }
            else
            {
                $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
            }
        }
        else
        {
            
            $responseArr = array('result'=>'0', 'data'=> [], 'message'=>'No Data Found');
        }
        return response()->json($responseArr);
    }

}
?>