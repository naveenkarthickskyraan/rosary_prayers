<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RosaryCategory;
use App\Models\RosaryLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RosaryCategoryController extends Controller
{
    // List
    public function index(Request $request)
    {
        $query = RosaryCategory::with('language')
            ->withCount(['subCategories', 'prayers'])
            ->orderBy('language_id');

        if ($request->language_id) {
            $query->where('language_id', $request->language_id);
        }

        $categories = $query->get();
        $languages  = RosaryLanguage::orderBy('id')->get();

        return view('admin.rosary.categories.index', compact('categories', 'languages'));
    }

    // Add form
    public function create()
    {
        $languages = RosaryLanguage::orderBy('id')->get();

        return view('admin.rosary.categories.create', compact('languages'));
    }

    // Save
    public function store(Request $request)
    {
        $request->validate([
            'language_id' => 'required|exists:rosary_languages,id',
            'name'        => 'required|string|max:255',
            'icon'        => 'nullable|image|max:2048',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        RosaryCategory::create([
            'language_id' => $request->language_id,
            'slug'        => $this->uniqueSlug($request->name),
            'name'        => $request->name,
            'icon'        => $this->upload($request, 'icon'),
            'sort_order'  => $request->sort_order ?? 0,
        ]);

        return redirect('admin/rosary-categories?language_id=' . $request->language_id)
            ->with('success', 'Category added successfully');
    }

    // Edit form
    public function edit($id)
    {
        $category  = RosaryCategory::findOrFail($id);
        $languages = RosaryLanguage::orderBy('id')->get();

        return view('admin.rosary.categories.edit', compact('category', 'languages'));
    }

    // Update (language is fixed after the category is created)
    public function update(Request $request, $id)
    {
        $category = RosaryCategory::findOrFail($id);

        $request->validate([
            'language_id' => 'required|exists:rosary_languages,id',
            'name'        => 'required|string|max:255',
            'icon'        => 'nullable|image|max:2048',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $data = [
            'language_id' => $request->language_id,
            'name'        => $request->name,
            'sort_order'  => $request->sort_order ?? 0,
        ];

        // Replace the icon only when a new file is uploaded
        if ($request->hasFile('icon')) {
            $data['icon'] = $this->upload($request, 'icon');
        }

        DB::transaction(function () use ($category, $data, $request) {
            $category->update($data);

            // Language changed: move its sub categories and prayers to the new language too
            DB::table('rosary_sub_categories')
                ->where('category_id', $category->id)
                ->update(['language_id' => $request->language_id]);

            DB::table('rosary_prayers')
                ->where('category_id', $category->id)
                ->update(['language_id' => $request->language_id]);
        });

        return redirect('admin/rosary-categories?language_id=' . $request->language_id)
            ->with('success', 'Category updated successfully');
    }

    // Delete (its sub categories and prayers are deleted too, because of the cascade)
    public function destroy($id)
    {
        $category = RosaryCategory::findOrFail($id);
        $langId   = $category->language_id;
        $category->delete();

        return redirect('admin/rosary-categories?language_id=' . $langId)
            ->with('success', 'Category deleted successfully');
    }

    // Save the icon in public/rosary/icons and return its full URL
    private function upload(Request $request, $field)
    {
        if (!$request->hasFile($field)) {
            return null;
        }

        $file     = $request->file($field);
        $fileName = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('rosary/icons'), $fileName);

        return url('rosary/icons/' . $fileName);
    }

    // Tamil or Hindi names give an empty slug, so fall back to "item"
    private function uniqueSlug($name)
    {
        $slug = Str::slug($name);
        if ($slug === '') {
            $slug = 'item';
        }

        $original = $slug;
        $i = 1;

        while (RosaryCategory::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $i++;
        }

        return $slug;
    }
}