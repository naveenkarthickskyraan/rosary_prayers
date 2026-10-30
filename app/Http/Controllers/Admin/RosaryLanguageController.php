<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RosaryLanguage;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RosaryLanguageController extends Controller
{
    public function index()
    {
        $languages = RosaryLanguage::orderBy('id')->get();

        return view('admin.rosary.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('admin.rosary.languages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'lang_name' => 'required|string|max:100',
            'lang_code' => 'required|string|max:10|unique:rosary_languages,lang_code',
        ]);

        RosaryLanguage::create([
            'lang_name' => $request->lang_name,
            'lang_code' => strtolower(trim($request->lang_code)),
        ]);

        return redirect('admin/rosary-languages')->with('success', 'Language added successfully');
    }

    public function edit($id)
    {
        $language = RosaryLanguage::findOrFail($id);

        return view('admin.rosary.languages.edit', compact('language'));
    }

    public function update(Request $request, $id)
    {
        $language = RosaryLanguage::findOrFail($id);

        $request->validate([
            'lang_name' => 'required|string|max:100',
            'lang_code' => 'required|string|max:10|unique:rosary_languages,lang_code,' . $language->id,
        ]);

        $language->update([
            'lang_name' => $request->lang_name,
            'lang_code' => strtolower(trim($request->lang_code)),
        ]);

        return redirect('admin/rosary-languages')->with('success', 'Language updated successfully');
    }

    // Once prayers are mapped, a language that is in use cannot be deleted
    public function destroy($id)
    {
        $language = RosaryLanguage::findOrFail($id);

        try {
            $language->delete();
        } catch (QueryException $e) {
            return redirect('admin/rosary-languages')
                ->with('error', 'This language is used by prayers, so it cannot be deleted');
        }

        return redirect('admin/rosary-languages')->with('success', 'Language deleted successfully');
    }
}