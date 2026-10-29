@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <ol class="breadcrumb p-2">
        <li class="breadcrumb-item"><i class="dripicons-home"></i> Home</li>
        <li class="breadcrumb-item active">Rosary Prayers</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <h4 class="card-title">Rosary Prayer List</h4>
                <a href="{{ url('admin/rosary-prayers/create?category_id='.request('category_id').'&sub_category_id='.request('sub_category_id')) }}" class="btn btn-primary">+ Add Prayer</a>
            </div>

            <form method="GET" action="{{ url('admin/rosary-prayers') }}" class="row g-2 mb-3" id="filterForm">
                <div class="col-md-4">
                    <select name="category_id" id="filterCategory" class="form-control">
                        <option value="">All Categories</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="sub_category_id" id="filterSub" class="form-control" onchange="this.form.submit()">
                        <option value="">All Sub Categories</option>
                        @foreach($subCategories as $s)
                            @if(!request('category_id') || request('category_id') == $s->category_id)
                                <option value="{{ $s->id }}" {{ request('sub_category_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped js-datatable" style="width:100%">
                    <thead style="background:#162133; color:#fff;">
                        <tr>
                            <th>ID</th>
                            <th class="no-sort">Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            {{-- <th>Sort</th> --}}
                            <th class="no-sort" width="160">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prayers as $p)
                            <tr>
                                <td>{{ $p->id }}</td>
                                <td>
                                    @if($p->thumbnail_url)
                                        <img src="{{ $p->thumbnail_url }}" width="60" height="40" style="object-fit:cover;border-radius:4px;">
                                    @endif
                                </td>
                                <td>{{ $p->title }}<br><small class="text-muted">{{ $p->subtitle }}</small></td>
                                <td>{{ $p->category->name ?? '-' }}</td>
                                <td>{{ $p->subCategory->name ?? '-' }}</td>
                                {{-- <td>{{ $p->sort_order }}</td> --}}
                                <td>
                                    <a href="{{ url('admin/rosary-prayers/'.$p->id.'/edit') }}" class="btn btn-sm btn-info">Edit</a>
                                    <a href="{{ url('admin/rosary-prayers/'.$p->id.'/delete') }}" class="btn btn-sm btn-danger"
                                       onclick="return confirm('Delete this prayer?')">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('filterCategory').addEventListener('change', function () {
    document.getElementById('filterSub').value = '';
    document.getElementById('filterForm').submit();
});
</script>

@include('admin.rosary._datatable')
@endsection