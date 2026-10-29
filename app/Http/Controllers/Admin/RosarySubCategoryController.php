<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RosaryCategory;
use App\Models\RosarySubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RosarySubCategoryController extends Controller
{
    // List (optional filter by category)
    public function index(Request $request)
    {
        $query = RosarySubCategory::with('category')
            ->withCount('prayers')
            ->orderBy('category_id')
            ->orderBy('sort_order');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $subCategories = $query->paginate(20)->appends($request->query());
        $categories    = RosaryCategory::orderBy('sort_order')->get();

        return view('admin.rosary.sub_categories.index', compact('subCategories', 'categories'));
    }

    // Add form
    public function create()
    {
        $categories = RosaryCategory::orderBy('sort_order')->get();

        return view('admin.rosary.sub_categories.create', compact('categories'));
    }

    // Save
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:rosary_categories,id',
            'name'        => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            // 'image'       => 'nullable|image|max:2048',
            // 'sort_order'  => 'nullable|integer|min:0',
        ]);

        RosarySubCategory::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'subtitle'    => $request->subtitle,
            'image_url'   => $this->upload($request, 'image'),
            'sort_order'  => $request->sort_order ?? 0,
        ]);

        return redirect('admin/rosary-sub-categories?category_id=' . $request->category_id)
            ->with('success', 'Sub category added successfully');
    }

    // Edit form
    public function edit($id)
    {
        $subCategory = RosarySubCategory::findOrFail($id);
        $categories  = RosaryCategory::orderBy('sort_order')->get();

        return view('admin.rosary.sub_categories.edit', compact('subCategory', 'categories'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $subCategory = RosarySubCategory::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:rosary_categories,id',
            'name'        => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'image'       => 'nullable|image|max:2048',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'subtitle'    => $request->subtitle,
            'sort_order'  => $request->sort_order ?? 0,
        ];

        // Replace the image only when a new file is uploaded
        if ($request->hasFile('image')) {
            $data['image_url'] = $this->upload($request, 'image');
        }

        $subCategory->update($data);

        return redirect('admin/rosary-sub-categories?category_id=' . $request->category_id)
            ->with('success', 'Sub category updated successfully');
    }

    // Delete (its prayers are deleted too, because of the cascade foreign key)
    public function destroy($id)
    {
        $subCategory = RosarySubCategory::findOrFail($id);
        $categoryId  = $subCategory->category_id;
        $subCategory->delete();

        return redirect('admin/rosary-sub-categories?category_id=' . $categoryId)
            ->with('success', 'Sub category deleted successfully');
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