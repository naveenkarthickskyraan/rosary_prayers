@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <ol class="breadcrumb p-2">
        <li class="breadcrumb-item"><i class="dripicons-home"></i> Home</li>
        <li class="breadcrumb-item">Rosary Prayers</li>
        <li class="breadcrumb-item active">Add</li>
    </ol>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Add Prayer</h4>
            <form method="POST" action="{{ url('admin/rosary-prayers') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.rosary.prayers._form')
            </form>
        </div>
    </div>
</div>
@endsection