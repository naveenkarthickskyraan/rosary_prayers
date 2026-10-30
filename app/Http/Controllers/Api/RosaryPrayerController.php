<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RosaryPrayerController extends Controller
{
    // 0. /api/languages
    public function languages()
    {
        $resArr = DB::table('rosary_languages')
            ->orderBy('id')
            ->get()
            ->map(function ($l) {
                return [
                    'id'       => $l->id,
                    'langName' => $l->lang_name,
                    'langCode' => $l->lang_code,
                ];
            })
            ->values()->toArray();

        return $this->response($resArr);
    }

    // 1. /api/categories   params: lang_code (default en)
    public function categories(Request $request)
    {
        $lang = $this->getLanguage($request->lang_code);

        $resArr = DB::table('rosary_categories')
            ->where('language_id', $lang->id)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($c) use ($lang) {
                return [
                    'id'       => $c->id,
                    'langId'   => $lang->id,
                    'langCode' => $lang->lang_code,
                    'name'     => $c->name,
                    'icon'     => $c->icon ?? '',
                ];
            })
            ->values()->toArray();

        return $this->response($resArr);
    }

    // 2. POST /api/sub-categories   params: id (category id), lang_code, page
    public function subCategories(Request $request)
    {
        $lang  = $this->getLanguage($request->lang_code);
        $page  = max((int) $request->page, 1);
        $limit = 15;

        $paginator = DB::table('rosary_sub_categories')
            ->where('language_id', $lang->id)
            ->where('category_id', $request->id)
            ->orderBy('sort_order')
            ->paginate($limit, ['*'], 'page', $page);

        $resArr = collect($paginator->items())->map(function ($s) use ($lang) {
            return [
                'category_id'  => $s->category_id,
                'sub_category' => $s->id,
                'langId'       => $lang->id,
                'langCode'     => $lang->lang_code,
                'name'         => $s->name,
                'subtitle'     => $s->subtitle ?? '',
                // 'imageUrl'  => $s->image_url ?? '',
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
    //     or: id (prayers placed directly in a category), lang_code, page
    public function categoryPrayers(Request $request)
    {
        $lang  = $this->getLanguage($request->lang_code);
        $page  = max((int) $request->page, 1);
        $limit = 15;

        $query = DB::table('rosary_prayers')
            ->where('language_id', $lang->id);

        if ($request->filled('sub_category_id')) {
            $query->where('sub_category_id', $request->sub_category_id);
        } else {
            $query->where('category_id', $request->id)
                  ->whereNull('sub_category_id');
        }

        $paginator = $query->orderBy('sort_order')
            ->paginate($limit, ['*'], 'page', $page);

        $resArr = collect($paginator->items())->map(function ($p) use ($lang) {
            $parts = preg_split("/\R{2,}/", trim($p->body ?? ''));

            return [
                'id'            => $p->id,
                'langId'        => $lang->id,
                'langCode'      => $lang->lang_code,
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
    //         'heading'       => $prayer->heading ?? ' ',
    //         'imageUrl'      => $prayer->thumbnail_url ?? '',
    //         'paragraphs'    => $parts,
    //     ];

    //     return $this->response($resArr);
    // }

    // lang_code -> language row (id, lang_code). Unknown or empty code falls back to en.
    private function getLanguage($code)
    {
        $lang = DB::table('rosary_languages')
            ->where('lang_code', $code ?: 'en')
            ->first();

        if (!$lang) {
            $lang = DB::table('rosary_languages')->where('lang_code', 'en')->first();
        }

        return $lang;
    }

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