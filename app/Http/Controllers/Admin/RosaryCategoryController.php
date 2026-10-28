<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RosaryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RosaryCategoryController extends Controller
{
    // List
    public function index()
    {
        $categories = RosaryCategory::withCount('prayers')
            ->orderBy('sort_order')
            ->get();

        return view('admin.rosary.categories.index', compact('categories'));
    }

    // Add form
    public function create()
    {
        return view('admin.rosary.categories.create');
    }

    // Save
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'icon'       => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        RosaryCategory::create([
            'slug'       => $this->uniqueSlug($request->name),
            'name'       => $request->name,
            'icon'       => $this->upload($request, 'icon'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect('admin/rosary-categories')->with('success', 'Category added successfully');
    }

    // Edit form
    public function edit($id)
    {
        $category = RosaryCategory::findOrFail($id);

        return view('admin.rosary.categories.edit', compact('category'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $category = RosaryCategory::findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:255',
            'icon'       => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = [
            'name'       => $request->name,
            'sort_order' => $request->sort_order ?? 0,
        ];

        // Replace the icon only when a new file is uploaded
        if ($request->hasFile('icon')) {
            $data['icon'] = $this->upload($request, 'icon');
        }

        $category->update($data);

        return redirect('admin/rosary-categories')->with('success', 'Category updated successfully');
    }

    // Delete (its prayers are deleted too, because of the cascade foreign key)
    public function destroy($id)
    {
        RosaryCategory::findOrFail($id)->delete();

        return redirect('admin/rosary-categories')->with('success', 'Category deleted successfully');
    }

    // Move the uploaded icon to public/rosary/icons and return its full URL
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

    private function uniqueSlug($name)
    {
        $slug = Str::slug($name);
        $original = $slug;
        $i = 1;

        while (RosaryCategory::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $i++;
        }

        return $slug;
    }
}