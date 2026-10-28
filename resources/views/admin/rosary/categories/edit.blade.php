@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <ol class="breadcrumb p-2">
        <li class="breadcrumb-item"><i class="dripicons-home"></i> Home</li>
        <li class="breadcrumb-item">Rosary Categories</li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Edit Category</h4>
            <form method="POST" action="{{ url('admin/rosary-categories/'.$category->id.'/update') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.rosary.categories._form')
            </form>
        </div>
    </div>
</div>
@endsection