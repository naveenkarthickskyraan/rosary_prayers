@php $subCategory = $subCategory ?? null; @endphp

@if($errors->any())
    <div class="alert">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Language <span class="text-danger">*</span></label>
        <select name="language_id" id="languageSelect" class="form-control" required>
            <option value="">-- Select Language --</option>
            @foreach($languages as $l)
                <option value="{{ $l->id }}"
                    {{ old('language_id', $subCategory->language_id ?? request('language_id')) == $l->id ? 'selected' : '' }}>
                    {{ $l->lang_name }} ({{ $l->lang_code }})
                </option>
            @endforeach
        </select>
        @if($subCategory)
            <small class="text-danger">Changing the language also moves its prayers. Pick a category of the new language.</small>
        @endif
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Category <span class="text-danger">*</span></label>
        <select name="category_id" id="categorySelect" class="form-control" required></select>
        <small class="text-muted">Only categories of the chosen language are shown.</small>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $subCategory->name ?? '') }}" placeholder="Morning Prayer" required>
</div>

<div class="mb-3">
    <label class="form-label">Subtitle</label>
    <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $subCategory->subtitle ?? '') }}" placeholder="Start your day with gratitude and trust in god">
</div>

{{-- <div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Image</label>
        <input type="file" name="image" class="form-control" accept="image/*" onchange="previewSubImg(this)">
        <img id="subImgPreview" src="{{ $subCategory->image_url ?? '' }}" width="160"
             style="margin-top:8px;border-radius:6px;{{ empty($subCategory->image_url) ? 'display:none;' : '' }}">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" min="0" class="form-control" value="{{ old('sort_order', $subCategory->sort_order ?? 0) }}">
    </div>
</div> --}}

<button type="submit" class="btn btn-primary">Save</button>
<a href="{{ url('admin/rosary-sub-categories') }}" class="btn btn-secondary">Cancel</a>

<script>
function previewSubImg(input) {
    var img = document.getElementById('subImgPreview');
    if (input.files && input.files[0]) {
        img.src = URL.createObjectURL(input.files[0]);
        img.style.display = 'block';
    }
}

var allCategories = @json($categories->map(function ($c) {
    return ['id' => $c->id, 'name' => $c->name, 'language_id' => $c->language_id];
})->values());

var selectedCategory = '{{ old('category_id', $subCategory->category_id ?? request('category_id')) }}';
var langSel = document.getElementById('languageSelect');
var catSel  = document.getElementById('categorySelect');

// Rebuild the category list with only the chosen language's categories
function loadCategories() {
    var lang = langSel.value;
    var list = allCategories.filter(function (c) {
        return String(c.language_id) === String(lang);
    });

    catSel.innerHTML = '';

    var first = document.createElement('option');
    first.value = '';
    if (!lang) {
        first.textContent = '-- Select language first --';
    } else if (list.length === 0) {
        first.textContent = '-- No category in this language. Add one first --';
    } else {
        first.textContent = '-- Select Category --';
    }
    catSel.appendChild(first);

    list.forEach(function (c) {
        var opt = document.createElement('option');
        opt.value = c.id;
        opt.textContent = c.name;
        if (String(c.id) === String(selectedCategory)) {
            opt.selected = true;
        }
        catSel.appendChild(opt);
    });
}

langSel.addEventListener('change', function () {
    selectedCategory = '';
    loadCategories();
});

loadCategories();
</script>