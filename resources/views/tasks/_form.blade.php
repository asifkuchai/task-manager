@csrf

<div class="mb-3">
    <label for="title" class="form-label">Task Title</label>
    <input type="text" name="title" id="title" class="form-control"
           value="{{ old('title', $task->title ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $task->description ?? '') }}</textarea>
</div>

<button type="submit" class="btn btn-primary">
    {{ $buttonText }}
</button>
<a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
