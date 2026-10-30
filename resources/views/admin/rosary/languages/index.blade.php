@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <ol class="breadcrumb p-2">
        <li class="breadcrumb-item"><i class="dripicons-home"></i> Home</li>
        <li class="breadcrumb-item active">Rosary Languages</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <h4 class="card-title">Rosary Language List</h4>
                <a href="{{ url('admin/rosary-languages/create') }}" class="btn btn-primary">+ Add Language</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped js-datatable" style="width:100%">
                    <thead style="background:#162133; color:#fff;">
                        <tr>
                            <th>ID</th>
                            <th>Language Name</th>
                            <th>Language Code</th>
                            <th class="no-sort" width="160">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($languages as $l)
                            <tr>
                                <td>{{ $l->id }}</td>
                                <td>{{ $l->lang_name }}</td>
                                <td>{{ $l->lang_code }}</td>
                                <td>
                                    <a href="{{ url('admin/rosary-languages/'.$l->id.'/edit') }}" class="btn btn-sm btn-info">Edit</a>
                                    <a href="{{ url('admin/rosary-languages/'.$l->id.'/delete') }}" class="btn btn-sm btn-danger"
                                       onclick="return confirm('Delete this language?')">Delete</a>
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