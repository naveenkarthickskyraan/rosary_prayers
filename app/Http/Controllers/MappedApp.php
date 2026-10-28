<?php

namespace App\Http\Controllers;
use App\Models\AppList;
use App\Models\MappedAppCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\Helpers\LogActivity;
class MappedApp extends Controller
{
    public function insert(Request $request, $id)
    {
        $appId = $id; 
        $appIds = "";
        if ($request->filled('mapped_app_list') && $request->filled('app_id')) {
            $appId = $request->input('app_id');
            
            // $appIds = $request->input('mapped_app_list');
            $appIds = $request->input('mapped_app_list', []);
            if (empty($appIds)) {
                $appIds = '';
            } else {
                $appIds = implode(",", $appIds);
            }
            $data = [
                'mapped_app_list' => $appIds,
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            
            $tableName = 'apps';
            $where = "app_id='$appId'";
            $actionQry = $this->updateQuery($tableName, $data, $where);
            // print_r($actionQry);
            // exit;
            
            $updateQuery = DB::statement($actionQry);
            
            if ($updateQuery) {
                return back()->with(["success_message" => "App List Mapping Successfully!!"]);
            } else {
                return back()->with(["failer_message" => "App List Mapping Failed.."]);
            }
        } else {
            return back()->with(["failer_message" => "App Id Invalid.."]);
        }
    }

    private function updateQuery($tableName, $data, $where)
    {
        $query = "UPDATE $tableName SET ";
        $setValues = [];
        foreach ($data as $column => $value) {
            $setValues[] = "$column='$value'";
        }
        $query .= implode(", ", $setValues);
        $query .= " WHERE $where";
        
        return $query;
    }
}
