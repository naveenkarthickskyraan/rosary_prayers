<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RosaryPrayerController extends Controller
{
    // 1. /api/categories  (General Prayers screen)
    // "next" tells the app which API to call on tap:
    //   subCategories -> /api/sub-categories with id
    //   prayers       -> /api/categories/prayers with id
    public function categories(Request $request)
    {
        $subCounts = DB::table('rosary_sub_categories')
            ->select('category_id', DB::raw('COUNT(*) as c'))
            ->groupBy('category_id')
            ->pluck('c', 'category_id');

        $resArr = DB::table('rosary_categories')
            ->get()
            ->map(function ($c) use ($subCounts) {
                return [
                    'id'       => $c->id,
                    'name'     => $c->name,
                    'icon'     => $c->icon ?? '',
                ];
            })
            ->values()->toArray();

        return $this->response($resArr);
    }

    // 2. POST /api/sub-categories   params: id (category id), page
    public function subCategories(Request $request)
    {
        $page  = max((int) $request->page, 1);
        $limit = 15;

        $paginator = DB::table('rosary_sub_categories')
            ->where('category_id', $request->id)
            ->orderBy('sort_order')
            ->paginate($limit, ['*'], 'page', $page);
        $resArr = collect($paginator->items())->map(function ($s) {
            return [
                'category_id'   => $s->category_id,
                'sub_category'       => $s->id,
                'name'     => $s->name,
                'subtitle' => $s->subtitle ?? '',
                // 'imageUrl' => $s->image_url ?? '',
            ];
        })->values()->toArray();

        return $this->response($resArr, [
            'current_page' => $paginator->currentPage(),
            'last_page'    => $paginator->lastPage(),
            'total'        => $paginator->total(),
        ]);
    }

    // 3. POST /api/categories/prayers
    // params: sub_category_id (prayers of a sub category)
    //     or: id (prayers placed directly in a category), page
    
public function categoryPrayers(Request $request)
{
    $page  = max((int) $request->page, 1);
    $limit = 15;

    $query = DB::table('rosary_prayers');

    if ($request->filled('sub_category_id')) {
        $query->where('sub_category_id', $request->sub_category_id);
    } else {
        $query->where('category_id', $request->id)
              ->whereNull('sub_category_id');
    }

    $paginator = $query->orderBy('sort_order')
        ->paginate($limit, ['*'], 'page', $page);

    $resArr = collect($paginator->items())->map(function ($p) {
        $parts = preg_split(
            "/\R{2,}/",
            trim($p->body ?? '')
        );

        return [
            'id'            => $p->id,
            'categoryId'    => $p->category_id,
            'subCategoryId' => $p->sub_category_id,
            'title'         => $p->title,
            'subtitle'      => $p->subtitle ?? '',
            'heading'       => $p->heading ?? '',
            'imageUrl'      => $p->thumbnail_url ?? '',
            'paragraphs'    => $parts ?: [],
        ];
    })->values()->toArray();

    return $this->response($resArr, [
        'current_page' => $paginator->currentPage(),
        'last_page'    => $paginator->lastPage(),
        'total'        => $paginator->total(),
    ]);
}


    // 4. POST /api/prayers/detail   params: id (prayer id)
    // public function prayerDetail(Request $request)
    // {
    //     $prayer = DB::table('rosary_prayers')->where('id', $request->id)->first();

    //     if (!$prayer) {
    //         return $this->response([]);
    //     }

    //     $parts   = preg_split("/\R{2,}/", trim($prayer->body));
    //     // $heading = array_shift($parts);

    //     $resArr = [
    //         'id'            => $prayer->id,
    //         'categoryId'    => $prayer->category_id,
    //         'subCategoryId' => $prayer->sub_category_id,
    //         'title'         => $prayer->title,
    //         'subtitle'      => $prayer->subtitle ?? '',
    //          'heading'       => $prayer->heading ?? ' ',
    //         'imageUrl'      => $prayer->thumbnail_url ?? '',
    //         'paragraphs'    => $parts,
    //     ];

    //     return $this->response($resArr);
    // }

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