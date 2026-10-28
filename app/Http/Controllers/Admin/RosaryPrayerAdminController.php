<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RosaryCategory;
use App\Models\RosaryPrayer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RosaryPrayerAdminController extends Controller
{
    // List
    public function index(Request $request)
    {
        $query = RosaryPrayer::with('category')->orderBy('category_id')->orderBy('sort_order');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $prayers    = $query->paginate(20)->appends($request->query());
        $categories = RosaryCategory::orderBy('sort_order')->get();

        return view('admin.rosary.prayers.index', compact('prayers', 'categories'));
    }

    // Add form
    public function create()
    {
        $categories = RosaryCategory::orderBy('sort_order')->get();

        return view('admin.rosary.prayers.create', compact('categories'));
    }

    // Save
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:rosary_categories,id',
            'title'       => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'body'        => 'required|string',
            'thumbnail'   => 'nullable|image|max:2048',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        RosaryPrayer::create([
            'category_id'   => $request->category_id,
            'slug'          => $this->uniqueSlug($request->title),
            'title'         => $request->title,
            'subtitle'      => $request->subtitle,
            'body'          => $request->body,
            'thumbnail_url' => $this->upload($request, 'thumbnail', 'images'),
            'sort_order'    => $request->sort_order ?? 0,
        ]);

        return redirect('admin/rosary-prayers')->with('success', 'Prayer added successfully');
    }

    // Edit form
    public function edit($id)
    {
        $prayer     = RosaryPrayer::findOrFail($id);
        $categories = RosaryCategory::orderBy('sort_order')->get();

        return view('admin.rosary.prayers.edit', compact('prayer', 'categories'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $prayer = RosaryPrayer::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:rosary_categories,id',
            'title'       => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'body'        => 'required|string',
            'thumbnail'   => 'nullable|image|max:2048',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'subtitle'    => $request->subtitle,
            'body'        => $request->body,
            'sort_order'  => $request->sort_order ?? 0,
        ];

        // Replace the image only when a new file is uploaded
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_url'] = $this->upload($request, 'thumbnail', 'images');
        }

        $prayer->update($data);

        return redirect('admin/rosary-prayers')->with('success', 'Prayer updated successfully');
    }

    // Delete
    public function destroy($id)
    {
        RosaryPrayer::findOrFail($id)->delete();

        return redirect('admin/rosary-prayers')->with('success', 'Prayer deleted successfully');
    }

    // Move the uploaded file to public/rosary/{folder} and return its full URL
    private function upload(Request $request, $field, $folder)
    {
        if (!$request->hasFile($field)) {
            return null;
        }

        $file     = $request->file($field);
        $fileName = time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('rosary/' . $folder), $fileName);

        return url('rosary/' . $folder . '/' . $fileName);
    }

    private function uniqueSlug($title)
    {
        $slug = Str::slug($title);
        $original = $slug;
        $i = 1;

        while (RosaryPrayer::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $i++;
        }

        return $slug;
    }
}