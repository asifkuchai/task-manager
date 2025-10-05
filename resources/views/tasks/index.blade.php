@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Task List</h2>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary">Add Task</a>
</div>

{{-- Filter Buttons --}}
<div class="mb-3">
    <a href="{{ route('tasks.index', ['filter' => 'all']) }}" class="btn btn-sm {{ $filter == 'all' ? 'btn-dark' : 'btn-outline-dark' }}">All</a>
    <a href="{{ route('tasks.index', ['filter' => 'completed']) }}" class="btn btn-sm {{ $filter == 'completed' ? 'btn-dark' : 'btn-outline-dark' }}">Completed</a>
    <a href="{{ route('tasks.index', ['filter' => 'incomplete']) }}" class="btn btn-sm {{ $filter == 'incomplete' ? 'btn-dark' : 'btn-outline-dark' }}">Incomplete</a>
</div>

<ul id="task-list" class="list-group">
    @foreach($tasks as $task)
        <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $task->id }}">
            <div>
                <strong>{{ $task->title }}</strong>
                <br>
                <small>{{ $task->description }}</small>
                <br>
                <span class="badge {{ $task->is_completed ? 'bg-success' : 'bg-secondary' }}">
                    {{ $task->is_completed ? 'Completed' : 'Pending' }}
                </span>
            </div>

            <div class="d-flex gap-1">
                {{-- Toggle Completion --}}
                <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-sm btn-outline-primary">
                        {{ $task->is_completed ? 'Mark Incomplete' : 'Mark Complete' }}
                    </button>
                </form>

                {{-- Edit --}}
                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-warning">Edit</a>

                {{-- Delete --}}
                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this task?')">Delete</button>
                </form>
            </div>
        </li>
    @endforeach
</ul>
@endsection

@push('scripts')
<script>
$(function() {
    // Make task list sortable
    $("#task-list").sortable({
        update: function(event, ui) {
            var order = $(this).sortable('toArray', { attribute: 'data-id' });
            $.post("{{ route('tasks.reorder') }}", {
                _token: '{{ csrf_token() }}',
                order: order
            });
        }
    });
});
</script>
@endpush
