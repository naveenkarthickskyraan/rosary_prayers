<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RosaryCategory;
use App\Models\RosaryPrayer;
use App\Models\RosarySubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RosaryPrayerAdminController extends Controller
{
    // List with Category and Sub Category filters
    public function index(Request $request)
    {
        $query = RosaryPrayer::with(['category', 'subCategory'])
            ->orderBy('category_id')
            ->orderBy('sub_category_id')
            ->orderBy('sort_order');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->sub_category_id) {
            $query->where('sub_category_id', $request->sub_category_id);
        }

        $prayers       = $query->paginate(20)->appends($request->query());
        $categories    = RosaryCategory::orderBy('sort_order')->get();
        $subCategories = RosarySubCategory::orderBy('sort_order')->get();

        return view('admin.rosary.prayers.index', compact('prayers', 'categories', 'subCategories'));
    }

    // Add form
    public function create(Request $request)
    {
        // Coming from a sub category "Prayers" button: pick its category too
        if ($request->sub_category_id && !$request->category_id) {
            $sub = RosarySubCategory::find($request->sub_category_id);
            if ($sub) {
                $request->merge(['category_id' => $sub->category_id]);
            }
        }

        $categories    = RosaryCategory::orderBy('sort_order')->get();
        $subCategories = RosarySubCategory::orderBy('sort_order')->get();

        return view('admin.rosary.prayers.create', compact('categories', 'subCategories'));
    }

    // Save
    public function store(Request $request)
    {
        $request->validate([
            'category_id'     => 'required|exists:rosary_categories,id',
            'sub_category_id' => 'nullable|exists:rosary_sub_categories,id',
            'title'           => 'required|string|max:255',
            'subtitle'        => 'nullable|string|max:255',
            'body'            => 'required|string',
            'thumbnail'       => 'nullable|image|max:2048',
            // 'sort_order'      => 'nullable|integer|min:0',
        ], [
        'thumbnail.max' => 'Image size must not exceed 1 MB.',
        'thumbnail.image' => 'Please upload a valid image file.',
        ]);

        $this->checkSubBelongsToCategory($request);

        RosaryPrayer::create([
            'category_id'     => $request->category_id,
            'sub_category_id' => $request->sub_category_id ?: null,
            'slug'            => $this->uniqueSlug($request->title),
            'title'           => $request->title,
            'subtitle'        => $request->subtitle,
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
        $categories    = RosaryCategory::orderBy('sort_order')->get();
        $subCategories = RosarySubCategory::orderBy('sort_order')->get();

        return view('admin.rosary.prayers.edit', compact('prayer', 'categories', 'subCategories'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $prayer = RosaryPrayer::findOrFail($id);

        $request->validate([
            'category_id'     => 'required|exists:rosary_categories,id',
            'sub_category_id' => 'nullable|exists:rosary_sub_categories,id',
            'title'           => 'required|string|max:255',
            'subtitle'        => 'nullable|string|max:255',
            'body'            => 'required|string',
            'thumbnail'       => 'nullable|image|max:2048',
            'sort_order'      => 'nullable|integer|min:0',
        ]);

        $this->checkSubBelongsToCategory($request);

        $data = [
            'category_id'     => $request->category_id,
            'sub_category_id' => $request->sub_category_id ?: null,
            'title'           => $request->title,
            'subtitle'        => $request->subtitle,
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
        $url    = 'admin/rosary-prayers?category_id=' . $prayer->category_id
                . ($prayer->sub_category_id ? '&sub_category_id=' . $prayer->sub_category_id : '');
        $prayer->delete();

        return redirect($url)->with('success', 'Prayer deleted successfully');
    }

    // The chosen sub category must belong to the chosen category
    private function checkSubBelongsToCategory(Request $request)
    {
        if ($request->sub_category_id) {
            $ok = RosarySubCategory::where('id', $request->sub_category_id)
                ->where('category_id', $request->category_id)
                ->exists();

            if (!$ok) {
                abort(422, 'Selected sub category does not belong to the selected category');
            }
        }
    }

    private function listUrl(Request $request)
    {
        return 'admin/rosary-prayers?category_id=' . $request->category_id
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