@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <ol class="breadcrumb p-2">
        <li class="breadcrumb-item"><i class="dripicons-home"></i> Home</li>
        <li class="breadcrumb-item active">Rosary Sub Categories</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <h4 class="card-title">Rosary Sub Category List</h4>
                <a href="{{ url('admin/rosary-sub-categories/create?category_id='.request('category_id')) }}" class="btn btn-primary">+ Add Sub Category</a>
            </div>

            <form method="GET" action="{{ url('admin/rosary-sub-categories') }}" class="row g-2 mb-3">
                <div class="col-md-4">
                    <select name="category_id" class="form-control" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped js-datatable" style="width:100%">
                    <thead style="background:#162133; color:#fff;">
                        <tr>
                            <th>ID</th>
                            {{-- <th class="no-sort">Image</th> --}}
                            <th>Name</th>
                            <th>Category</th>
                            <th>Prayers</th>
                            {{-- <th>Sort</th> --}}
                            <th class="no-sort" width="240">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subCategories as $s)
                            <tr>
                                <td>{{ $s->id }}</td>
                                {{-- <td>
                                    @if($s->image_url)
                                        <img src="{{ $s->image_url }}" width="70" height="40" style="object-fit:cover;border-radius:4px;">
                                    @endif
                                </td> --}}
                                <td>{{ $s->name }}<br><small class="text-muted">{{ $s->subtitle }}</small></td>
                                <td>{{ $s->category->name ?? '-' }}</td>
                                <td>{{ $s->prayers_count }}</td>
                                {{-- <td>{{ $s->sort_order }}</td> --}}
                                <td>
                                    {{-- <a href="{{ url('admin/rosary-prayers?category_id='.$s->category_id.'&sub_category_id='.$s->id) }}" class="btn btn-sm btn-secondary">Prayers</a> --}}
                                    <a href="{{ url('admin/rosary-sub-categories/'.$s->id.'/edit') }}" class="btn btn-sm btn-info">Edit</a>
                                    <a href="{{ url('admin/rosary-sub-categories/'.$s->id.'/delete') }}" class="btn btn-sm btn-danger"
                                       onclick="return confirm('Delete this sub category and all its prayers?')">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('admin.rosary._datatable')
@endsection