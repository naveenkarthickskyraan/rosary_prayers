@php $language = $language ?? null; @endphp

@if($errors->any())
    <div class="alert">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Language Name <span class="text-danger">*</span></label>
        <input type="text" name="lang_name" class="form-control" value="{{ old('lang_name', $language->lang_name ?? '') }}" placeholder="Tamil" required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Language Code <span class="text-danger">*</span></label>
        <input type="text" name="lang_code" class="form-control" value="{{ old('lang_code', $language->lang_code ?? '') }}" placeholder="ta" maxlength="10" required>
        <small class="text-muted">Short code such as en, ta, hi, ml. It must be unique.</small>
    </div>
</div>

<button type="submit" class="btn btn-primary">Save</button>
<a href="{{ url('admin/rosary-languages') }}" class="btn btn-secondary">Cancel</a>