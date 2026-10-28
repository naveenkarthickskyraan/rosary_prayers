@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <ol class="breadcrumb p-2">
        <li class="breadcrumb-item"><i class="dripicons-home"></i> Home</li>
        <li class="breadcrumb-item active">Rosary Categories</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <h4 class="card-title">Rosary Category List</h4>
                <a href="{{ url('admin/rosary-categories/create') }}" class="btn btn-primary">+ Add Category</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead style="background:#162133; color:#fff;">
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Icon</th>
                            <th>Prayers</th>
                            <th>Sort Order</th>
                            <th width="160">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $key => $c)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $c->id }}</td>
                                <td>{{ $c->name }}</td>
                                <td>
                                    @if($c->icon)
                                        <img src="{{ $c->icon }}" width="36" style="background:#162133;padding:4px;border-radius:6px;">
                                    @endif
                                </td>
                                <td>{{ $c->prayers_count }}</td>
                                <td>{{ $c->sort_order }}</td>
                                <td>
                                    <a href="{{ url('admin/rosary-categories/'.$c->id.'/edit') }}" class="btn btn-sm btn-info">Edit</a>
                                    <a href="{{ url('admin/rosary-categories/'.$c->id.'/delete') }}" class="btn btn-sm btn-danger"
                                       onclick="return confirm('Delete this category and all its prayers?')">Delete</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No categories found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection