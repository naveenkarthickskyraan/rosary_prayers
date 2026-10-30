@php $category = $category ?? null; @endphp

@if($errors->any())
    <div class="alert">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="mb-3">
    <label class="form-label">Language <span class="text-danger">*</span></label>
    <select name="language_id" id="languageSelect" class="form-control" required>
        <option value="">-- Select Language --</option>
        @foreach($languages as $l)
            <option value="{{ $l->id }}"
                {{ old('language_id', $category->language_id ?? request('language_id')) == $l->id ? 'selected' : '' }}>
                {{ $l->lang_name }} ({{ $l->lang_code }})
            </option>
        @endforeach
    </select>
    @if($category)
        <small class="text-danger">
            Changing the language also moves this category's sub categories and prayers to the new language.
        </small>
    @endif
</div>

<div class="mb-3">
    <label class="form-label">Name <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" placeholder="Daily Prayer" required>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Icon (png, svg, jpg, webp)</label>
        <input type="file" name="icon" class="form-control" accept="image/*" onchange="previewIcon(this)">
        <img id="iconPreview" src="{{ $category->icon ?? '' }}" width="80"
             style="margin-top:8px;border-radius:6px;background:#162133;padding:6px;{{ empty($category->icon) ? 'display:none;' : '' }}">
    </div>
</div>    

{{-- <div class="mb-3">
    <label class="form-label">Sort Order</label>
    <input type="number" name="sort_order" min="0" class="form-control" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
</div> --}}

<button type="submit" class="btn btn-primary">Save</button>
<a href="{{ url('admin/rosary-categories') }}" class="btn btn-secondary">Cancel</a>

<script>
function previewIcon(input) {
    var img = document.getElementById('iconPreview');
    if (input.files && input.files[0]) {
        img.src = URL.createObjectURL(input.files[0]);
        img.style.display = 'block';
    }
}
</script>

@if($category)
<script>
var originalLang = '{{ $category->language_id }}';
document.querySelector('form').addEventListener('submit', function (e) {
    var now = document.getElementById('languageSelect').value;
    if (now !== originalLang) {
        if (!confirm('Change language? Its sub categories and prayers will also move to the new language.')) {
            e.preventDefault();
        }
    }
});
</script>
@endif