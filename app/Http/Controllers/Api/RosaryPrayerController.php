<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RosaryCategory;
use App\Models\RosaryPrayer;
use Illuminate\Http\Request;

class RosaryPrayerController extends Controller
{
    // 1. GET /api/categories
    public function categories()
    {
        $resArr = RosaryCategory::orderBy('sort_order')
            ->get()
            ->map(function ($c) {
                return [
                    'id'   => $c->id,
                    'name' => $c->name,
                    'icon' => $c->icon,
                ];
            })
            ->values()
            ->toArray();

        return $this->response($resArr);
    }

    // 2. GET /api/categories/{id}/prayers
    public function categoryPrayers(Request $request)
    {
        $page  = max((int) $request->page, 1);
        $limit = 15; // fixed page size

        $paginator = RosaryPrayer::where('category_id', $request->id)
            ->orderBy('sort_order')
            ->paginate($limit, ['*'], 'page', $page);

        $resArr = $paginator->getCollection()
            ->map(function ($p) {
                return [
                    'id'       => $p->id,
                    'title'    => $p->title,
                    'subtitle' => $p->subtitle,
                    'imageUrl' => $p->thumbnail_url,
                ];
            })
            ->values()
            ->toArray();

        return $this->response($resArr, [
            'current_page' => $paginator->currentPage(),
            'last_page'    => $paginator->lastPage(),
            'total'        => $paginator->total(),
        ]);
    }

    // 3. GET /api/prayers/{id}
    public function prayerDetail(Request $request)
    {
            $prayer = RosaryPrayer::find($request->id);


        if (!$prayer) {
            return $this->response([]);
        }

        $parts   = preg_split("/\R{2,}/", trim($prayer->body));
        $heading = array_shift($parts);

        $resArr = [
            'id'              => $prayer->id,
            'categoryId'      => $prayer->category_id,
            'title'           => $prayer->title,
            'subtitle'        => $prayer->subtitle,
            'imageUrl'        => $prayer->thumbnail_url,
            'heading'         => $heading,
            'paragraphs'      => $parts,
        ];

        return $this->response($resArr);
    }

    // Common response format
    private function response($resArr, $extra = [])
    {
        if (empty($resArr)) {
            return response()->json([
                'result'  => '0',
                'data'    => [],
                'message' => 'No Data Found',
            ]);
        }

        return response()->json(array_merge([
            'result'  => '1',
            'data'    => $resArr,
            'message' => 'Success',
        ], $extra));
    }
}