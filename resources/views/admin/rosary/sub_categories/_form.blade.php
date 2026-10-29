@php $subCategory = $subCategory ?? null; @endphp

@if($errors->any())
    <div class="alert">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="mb-3">
    <label class="form-label">Category <span class="text-danger">*</span></label>
    <select name="category_id" class="form-control" required>
        <option value="">-- Select Category --</option>
        @foreach($categories as $c)
            <option value="{{ $c->id }}" {{ old('category_id', $subCategory->category_id ?? request('category_id')) == $c->id ? 'selected' : '' }}>
                {{ $c->name }}
            </option>
        @endforeach
    </select>
    <small class="text-muted">Example: Daily Prayer</small>
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
</script>