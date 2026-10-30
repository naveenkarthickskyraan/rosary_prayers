<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RosaryCategory;
use App\Models\RosaryLanguage;
use App\Models\RosarySubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RosarySubCategoryController extends Controller
{
    // List (filter by language and category)
    public function index(Request $request)
    {
        $query = RosarySubCategory::with(['category', 'language'])
            ->withCount('prayers')
            ->orderBy('language_id')
            ->orderBy('category_id')
            ->orderBy('sort_order');

        if ($request->language_id) {
            $query->where('language_id', $request->language_id);
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $subCategories = $query->get();
        $languages     = RosaryLanguage::orderBy('id')->get();
        $categories    = RosaryCategory::when($request->language_id, function ($q) use ($request) {
                            $q->where('language_id', $request->language_id);
                        })->orderBy('sort_order')->get();

        return view('admin.rosary.sub_categories.index', compact('subCategories', 'languages', 'categories'));
    }

    // Add form
    public function create(Request $request)
    {
        // Coming from a category button: take its language too
        if ($request->category_id && !$request->language_id) {
            $cat = RosaryCategory::find($request->category_id);
            if ($cat) {
                $request->merge(['language_id' => $cat->language_id]);
            }
        }

        $languages  = RosaryLanguage::orderBy('id')->get();
        $categories = RosaryCategory::orderBy('language_id')->orderBy('sort_order')->get();

        return view('admin.rosary.sub_categories.create', compact('languages', 'categories'));
    }

    // Save
    public function store(Request $request)
    {
        $request->validate([
            'language_id' => 'required|exists:rosary_languages,id',
            'category_id' => 'required|exists:rosary_categories,id',
            'name'        => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'image'       => 'nullable|image|max:2048',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        if ($this->categoryNotInLanguage($request->category_id, $request->language_id)) {
            return back()->withInput()->withErrors(['category_id' => 'Selected category is not in the selected language']);
        }

        RosarySubCategory::create([
            'language_id' => $request->language_id,
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'subtitle'    => $request->subtitle,
            'image_url'   => $this->upload($request, 'image'),
            'sort_order'  => $request->sort_order ?? 0,
        ]);

        return redirect('admin/rosary-sub-categories?language_id=' . $request->language_id . '&category_id=' . $request->category_id)
            ->with('success', 'Sub category added successfully');
    }

    // Edit form
    public function edit($id)
    {
        $subCategory = RosarySubCategory::findOrFail($id);
        $languages   = RosaryLanguage::orderBy('id')->get();
        // all categories; the form filters them by the chosen language
        $categories  = RosaryCategory::orderBy('language_id')->orderBy('sort_order')->get();

        return view('admin.rosary.sub_categories.edit', compact('subCategory', 'languages', 'categories'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $subCategory = RosarySubCategory::findOrFail($id);

        $request->validate([
            'language_id' => 'required|exists:rosary_languages,id',
            'category_id' => 'required|exists:rosary_categories,id',
            'name'        => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'image'       => 'nullable|image|max:2048',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        if ($this->categoryNotInLanguage($request->category_id, $request->language_id)) {
            return back()->withInput()->withErrors(['category_id' => 'Selected category is not in the selected language']);
        }

        $data = [
            'language_id' => $request->language_id,
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'subtitle'    => $request->subtitle,
            'sort_order'  => $request->sort_order ?? 0,
        ];

        if ($request->hasFile('image')) {
            $data['image_url'] = $this->upload($request, 'image');
        }

        DB::transaction(function () use ($subCategory, $data, $request) {
            $subCategory->update($data);

            // Move its prayers to the same language and category
            DB::table('rosary_prayers')
                ->where('sub_category_id', $subCategory->id)
                ->update([
                    'language_id' => $request->language_id,
                    'category_id' => $request->category_id,
                ]);
        });

        return redirect('admin/rosary-sub-categories?language_id=' . $request->language_id . '&category_id=' . $request->category_id)
            ->with('success', 'Sub category updated successfully');
    }

    // Delete (its prayers are deleted too, because of the cascade foreign key)
    public function destroy($id)
    {
        $subCategory = RosarySubCategory::findOrFail($id);
        $url = 'admin/rosary-sub-categories?language_id=' . $subCategory->language_id . '&category_id=' . $subCategory->category_id;
        $subCategory->delete();

        return redirect($url)->with('success', 'Sub category deleted successfully');
    }

    // The category must be in the same language
    private function categoryNotInLanguage($categoryId, $languageId)
    {
        return RosaryCategory::where('id', $categoryId)
            ->where('language_id', $languageId)
            ->doesntExist();
    }

    // Save the image in public/rosary/sub-categories and return its full URL
    private function upload(Request $request, $field)
    {
        if (!$request->hasFile($field)) {
            return null;
        }

        $file     = $request->file($field);
        $fileName = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('rosary/sub-categories'), $fileName);

        return url('rosary/sub-categories/' . $fileName);
    }
}