@php $prayer = $prayer ?? null; @endphp

@if($errors->any())
    <div class="alert">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Category <span class="text-danger">*</span></label>
        <select name="category_id" id="categorySelect" class="form-control" required>
            <option value="">-- Select Category --</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}"
                    {{ old('category_id', $prayer->category_id ?? request('category_id')) == $c->id ? 'selected' : '' }}>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Sub Category</label>
        <select name="sub_category_id" id="subCategorySelect" class="form-control">
            <option value="">-- None --</option>
            @foreach($subCategories as $s)
                <option value="{{ $s->id }}" data-category="{{ $s->category_id }}"
                    {{ old('sub_category_id', $prayer->sub_category_id ?? request('sub_category_id')) == $s->id ? 'selected' : '' }}>
                    {{ $s->name }}
                </option>
            @endforeach
        </select>
        <small class="text-muted">Choose a category first. Leave "None" if the category has no sub categories.</small>
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
    <label class="form-label">Prayer Text <span class="text-danger">*</span></label>
    <textarea name="body" rows="14" class="form-control" required>{{ old('body', $prayer->body ?? '') }}</textarea>
    <small class="text-muted">
        First line = heading (e.g. "Heavenly Father,"). Leave one blank line between paragraphs.
    </small>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Image</label>
        <input type="file" name="thumbnail" class="form-control" accept="image/*" onchange="previewImg(this, 'thumbPreview')">
        <img id="thumbPreview" src="{{ $prayer->thumbnail_url ?? '' }}" width="120"
             style="margin-top:8px;border-radius:6px;{{ empty($prayer->thumbnail_url) ? 'display:none;' : '' }}">
             @error('thumbnail')
    <div class="text-danger mt-1">
                {{ $message }}
            </div>
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

// Show only the sub categories of the selected category
function filterSubCategories() {
    var cat = document.getElementById('categorySelect').value;
    var sub = document.getElementById('subCategorySelect');

    Array.from(sub.options).forEach(function (opt) {
        if (!opt.value) return;
        var show = opt.dataset.category === cat;
        opt.hidden = !show;
        opt.disabled = !show;
    });

    var selected = sub.options[sub.selectedIndex];
    if (selected && selected.value && selected.disabled) {
        sub.value = '';
    }
}

document.getElementById('categorySelect').addEventListener('change', function () {
    document.getElementById('subCategorySelect').value = '';
    filterSubCategories();
});
filterSubCategories();
</script>