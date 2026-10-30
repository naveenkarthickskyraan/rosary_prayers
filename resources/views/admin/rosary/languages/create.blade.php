@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <ol class="breadcrumb p-2">
        <li class="breadcrumb-item"><i class="dripicons-home"></i> Home</li>
        <li class="breadcrumb-item">Rosary Languages</li>
        <li class="breadcrumb-item active">Add</li>
    </ol>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Add Language</h4>
            <form method="POST" action="{{ url('admin/rosary-languages') }}">
                @csrf
                @include('admin.rosary.languages._form')
            </form>
        </div>
    </div>
</div>
@endsection