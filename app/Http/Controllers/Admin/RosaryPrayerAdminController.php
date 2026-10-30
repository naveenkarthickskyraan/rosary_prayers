<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RosaryCategory;
use App\Models\RosaryLanguage;
use App\Models\RosaryPrayer;
use App\Models\RosarySubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RosaryPrayerAdminController extends Controller
{
    // List (filters: language -> category -> sub category)
    public function index(Request $request)
    {
        $query = RosaryPrayer::with(['category', 'subCategory', 'language'])
            ->orderBy('language_id')
            ->orderBy('category_id')
            ->orderBy('sub_category_id')
            ->orderBy('sort_order');

        if ($request->language_id) {
            $query->where('language_id', $request->language_id);
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->sub_category_id) {
            $query->where('sub_category_id', $request->sub_category_id);
        }

        $prayers       = $query->get();
        $languages     = RosaryLanguage::orderBy('id')->get();
        $categories    = RosaryCategory::when($request->language_id, function ($q) use ($request) {
                            $q->where('language_id', $request->language_id);
                        })->orderBy('sort_order')->get();
        $subCategories = RosarySubCategory::when($request->category_id, function ($q) use ($request) {
                            $q->where('category_id', $request->category_id);
                        }, function ($q) use ($request) {
                            $q->when($request->language_id, function ($q2) use ($request) {
                                $q2->where('language_id', $request->language_id);
                            });
                        })->orderBy('sort_order')->get();

        return view('admin.rosary.prayers.index', compact('prayers', 'languages', 'categories', 'subCategories'));
    }

    // Add form
    public function create(Request $request)
    {
        // Coming from a sub category / category button: take the parents too
        if ($request->sub_category_id && !$request->category_id) {
            $sub = RosarySubCategory::find($request->sub_category_id);
            if ($sub) {
                $request->merge(['category_id' => $sub->category_id]);
            }
        }
        if ($request->category_id && !$request->language_id) {
            $cat = RosaryCategory::find($request->category_id);
            if ($cat) {
                $request->merge(['language_id' => $cat->language_id]);
            }
        }

        $languages     = RosaryLanguage::orderBy('id')->get();
        $categories    = RosaryCategory::orderBy('language_id')->orderBy('sort_order')->get();
        $subCategories = RosarySubCategory::orderBy('sort_order')->get();

        return view('admin.rosary.prayers.create', compact('languages', 'categories', 'subCategories'));
    }

    // Save
    public function store(Request $request)
    {
        $this->validateRequest($request);

        if ($error = $this->mappingError($request)) {
            return back()->withInput()->withErrors($error);
        }

        RosaryPrayer::create([
            'language_id'     => $request->language_id,
            'category_id'     => $request->category_id,
            'sub_category_id' => $request->sub_category_id ?: null,
            'slug'            => $this->uniqueSlug($request->title),
            'title'           => $request->title,
            'subtitle'        => $request->subtitle,
            'heading'         => $request->heading,
            'body'            => $request->body,
            'thumbnail_url'   => $this->upload($request, 'thumbnail'),
            'sort_order'      => $request->sort_order ?? 0,
        ]);

        return redirect($this->listUrl($request))->with('success', 'Prayer added successfully');
    }

    // Edit form
    public function edit($id)
    {
        $prayer        = RosaryPrayer::findOrFail($id);
        $languages     = RosaryLanguage::orderBy('id')->get();
        $categories    = RosaryCategory::orderBy('language_id')->orderBy('sort_order')->get();
        $subCategories = RosarySubCategory::orderBy('sort_order')->get();

        return view('admin.rosary.prayers.edit', compact('prayer', 'languages', 'categories', 'subCategories'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $prayer = RosaryPrayer::findOrFail($id);

        $this->validateRequest($request);

        if ($error = $this->mappingError($request)) {
            return back()->withInput()->withErrors($error);
        }

        $data = [
            'language_id'     => $request->language_id,
            'category_id'     => $request->category_id,
            'sub_category_id' => $request->sub_category_id ?: null,
            'title'           => $request->title,
            'subtitle'        => $request->subtitle,
            'heading'         => $request->heading,
            'body'            => $request->body,
            'sort_order'      => $request->sort_order ?? 0,
        ];

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_url'] = $this->upload($request, 'thumbnail');
        }

        $prayer->update($data);

        return redirect($this->listUrl($request))->with('success', 'Prayer updated successfully');
    }

    // Delete
    public function destroy($id)
    {
        $prayer = RosaryPrayer::findOrFail($id);
        $url    = 'admin/rosary-prayers?language_id=' . $prayer->language_id . '&category_id=' . $prayer->category_id
                . ($prayer->sub_category_id ? '&sub_category_id=' . $prayer->sub_category_id : '');
        $prayer->delete();

        return redirect($url)->with('success', 'Prayer deleted successfully');
    }

    private function validateRequest(Request $request)
    {
        $request->validate([
            'language_id'     => 'required|exists:rosary_languages,id',
            'category_id'     => 'required|exists:rosary_categories,id',
            'sub_category_id' => 'nullable|exists:rosary_sub_categories,id',
            'title'           => 'required|string|max:255',
            'subtitle'        => 'nullable|string|max:255',
            'heading'         => 'nullable|string|max:255',
            'body'            => 'required|string',
            'thumbnail'       => 'nullable|image|max:2048',
            'sort_order'      => 'nullable|integer|min:0',
        ]);
    }

    // Category must be in the chosen language, sub category must belong to that category
    private function mappingError(Request $request)
    {
        $catOk = RosaryCategory::where('id', $request->category_id)
            ->where('language_id', $request->language_id)->exists();

        if (!$catOk) {
            return ['category_id' => 'Selected category is not in the selected language'];
        }

        if ($request->sub_category_id) {
            $subOk = RosarySubCategory::where('id', $request->sub_category_id)
                ->where('category_id', $request->category_id)
                ->where('language_id', $request->language_id)->exists();

            if (!$subOk) {
                return ['sub_category_id' => 'Selected sub category does not belong to the selected category'];
            }
        }

        return null;
    }

    private function listUrl(Request $request)
    {
        return 'admin/rosary-prayers?language_id=' . $request->language_id . '&category_id=' . $request->category_id
            . ($request->sub_category_id ? '&sub_category_id=' . $request->sub_category_id : '');
    }

    private function upload(Request $request, $field)
    {
        if (!$request->hasFile($field)) {
            return null;
        }

        $file     = $request->file($field);
        $fileName = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('rosary/images'), $fileName);

        return url('rosary/images/' . $fileName);
    }

    // Tamil or Hindi titles give an empty slug, so fall back to "prayer"
    private function uniqueSlug($title)
    {
        $slug = Str::slug($title);
        if ($slug === '') {
            $slug = 'prayer';
        }

        $original = $slug;
        $i = 1;

        while (RosaryPrayer::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $i++;
        }

        return $slug;
    }
}