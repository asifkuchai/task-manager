@extends('layouts.app')

@section('content')
<h2 class="mb-4">Edit Task</h2>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('tasks.update', $task->id) }}" method="POST">
    @csrf
    @method('PUT')
    @include('tasks._form', ['buttonText' => 'Update Task'])
</form>
@endsection
