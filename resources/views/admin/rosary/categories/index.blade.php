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
                <table class="table table-bordered table-striped js-datatable" style="width:100%">
                    <thead style="background:#162133; color:#fff;">
                        <tr>
                            <th>ID</th>
                            <th class="no-sort">Icon</th>
                            <th>Name</th>
                            <th>Language</th>
                            {{-- <th>Prayers</th> --}}
                            <th class="no-sort" width="300">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $c)
                            <tr>
                                <td>{{ $c->id }}</td>
                                <td>
                                    @if($c->icon)
                                        <img src="{{ $c->icon }}" width="40" style="background:#162133;padding:4px;border-radius:6px;">
                                    @endif
                                </td>
                                <td>{{ $c->name }}<br><small class="text-muted">{{ $c->subtitle }}</small></td>
                                <td>{{ $c->language->lang_name }}</td>
                                {{-- <td>{{ $c->prayers_count }}</td> --}}
                                {{-- <td>{{ $c->sort_order }}</td> --}}
                                <td>
                                    {{-- <a href="{{ url('admin/rosary-sub-categories?category_id='.$c->id) }}" class="btn btn-sm btn-dark">Sub Categories</a>
                                    <a href="{{ url('admin/rosary-prayers?category_id='.$c->id) }}" class="btn btn-sm btn-secondary">Prayers</a> --}}
                                    <a href="{{ url('admin/rosary-categories/'.$c->id.'/edit') }}" class="btn btn-sm btn-info">Edit</a>
                                    <a href="{{ url('admin/rosary-categories/'.$c->id.'/delete') }}" class="btn btn-sm btn-danger"
                                       onclick="return confirm('Delete this category with its sub categories and prayers?')">Delete</a>
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