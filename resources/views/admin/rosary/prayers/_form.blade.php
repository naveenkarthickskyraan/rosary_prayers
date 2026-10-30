@php $prayer = $prayer ?? null; @endphp

@if($errors->any())
    <div class="alert">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Language <span class="text-danger">*</span></label>
        <select name="language_id" id="languageSelect" class="form-control" required>
            <option value="">-- Select Language --</option>
            @foreach($languages as $l)
                <option value="{{ $l->id }}"
                    {{ old('language_id', $prayer->language_id ?? request('language_id')) == $l->id ? 'selected' : '' }}>
                    {{ $l->lang_name }} ({{ $l->lang_code }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Category <span class="text-danger">*</span></label>
        <select name="category_id" id="categorySelect" class="form-control" required></select>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Sub Category</label>
        <select name="sub_category_id" id="subCategorySelect" class="form-control"></select>
        <small class="text-muted">Choose "None" if the category has no sub categories.</small>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Title <span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $prayer->title ?? '') }}" placeholder="Prayer title 1" required>
</div>

<div class="mb-3">
    <label class="form-label">Subtitle</label>
    <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $prayer->subtitle ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Heading</label>
    <input type="text" name="heading" class="form-control" value="{{ old('heading', $prayer->heading ?? '') }}" placeholder="Heavenly Father,">
</div>

<div class="mb-3">
    <label class="form-label">Prayer Text <span class="text-danger">*</span></label>
    <textarea name="body" rows="14" class="form-control" required>{{ old('body', $prayer->body ?? '') }}</textarea>
    <small class="text-muted">Leave one blank line between paragraphs.</small>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Image</label>
        <input type="file" name="thumbnail" class="form-control" accept="image/*" onchange="previewImg(this, 'thumbPreview')">
        <img id="thumbPreview" src="{{ $prayer->thumbnail_url ?? '' }}" width="120"
             style="margin-top:8px;border-radius:6px;{{ empty($prayer->thumbnail_url) ? 'display:none;' : '' }}">
        @error('thumbnail')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- <div class="col-md-6 mb-3">
        <label class="form-label">Sort Order</label>
        <input type="number" name="sort_order" min="0" class="form-control" value="{{ old('sort_order', $prayer->sort_order ?? 0) }}">
    </div> --}}
</div>

<button type="submit" class="btn btn-primary">Save</button>
<a href="{{ url('admin/rosary-prayers') }}" class="btn btn-secondary">Cancel</a>

<script>
function previewImg(input, targetId) {
    var img = document.getElementById(targetId);
    if (input.files && input.files[0]) {
        img.src = URL.createObjectURL(input.files[0]);
        img.style.display = 'block';
    }
}

var allCategories = @json($categories->map(function ($c) {
    return ['id' => $c->id, 'name' => $c->name, 'language_id' => $c->language_id];
})->values());

var allSubCategories = @json($subCategories->map(function ($s) {
    return ['id' => $s->id, 'name' => $s->name, 'category_id' => $s->category_id];
})->values());

var selectedCategory = '{{ old('category_id', $prayer->category_id ?? request('category_id')) }}';
var selectedSub      = '{{ old('sub_category_id', $prayer->sub_category_id ?? request('sub_category_id')) }}';

var langSel = document.getElementById('languageSelect');
var catSel  = document.getElementById('categorySelect');
var subSel  = document.getElementById('subCategorySelect');

function fill(select, firstText, list, selectedValue) {
    select.innerHTML = '';
    var first = document.createElement('option');
    first.value = '';
    first.textContent = firstText;
    select.appendChild(first);

    list.forEach(function (item) {
        var opt = document.createElement('option');
        opt.value = item.id;
        opt.textContent = item.name;
        if (String(item.id) === String(selectedValue)) { opt.selected = true; }
        select.appendChild(opt);
    });
}

// Language -> only its categories
function loadCategories() {
    var lang = langSel.value;
    var list = allCategories.filter(function (c) { return String(c.language_id) === String(lang); });
    var text = !lang ? '-- Select language first --'
             : (list.length === 0 ? '-- No category in this language --' : '-- Select Category --');
    fill(catSel, text, list, selectedCategory);
    loadSubCategories();
}

// Category -> only its sub categories
function loadSubCategories() {
    var cat  = catSel.value;
    var list = allSubCategories.filter(function (s) { return String(s.category_id) === String(cat); });
    fill(subSel, '-- None --', list, selectedSub);
}

langSel.addEventListener('change', function () {
    selectedCategory = '';
    selectedSub = '';
    loadCategories();
});
catSel.addEventListener('change', function () {
    selectedSub = '';
    loadSubCategories();
});

loadCategories();
</script>