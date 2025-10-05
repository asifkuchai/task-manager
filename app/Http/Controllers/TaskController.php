<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');

        $query = Task::query();

        if ($filter === 'completed') {
            $query->completed();
        } elseif ($filter === 'incomplete') {
            $query->incomplete();
        }

        $tasks = $query->ordered()->get();

        return view('tasks.index', compact('tasks', 'filter'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $maxPosition = Task::max('position') ?? 0;

        Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'position' => $maxPosition + 1,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }

    /**
     * Toggle task completion.
     */
    public function toggle(Task $task)
    {
        $task->update([
            'is_completed' => !$task->is_completed,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task status updated!');
    }

    /**
     * Reorder tasks.
     */
    public function reorder(Request $request)
    {
        $order = $request->input('order'); // expects array of task IDs in new order

        if (is_array($order)) {
            foreach ($order as $index => $id) {
                Task::where('id', $id)->update(['position' => $index + 1]);
            }
        }

        return response()->json(['success' => true]);
    }
}
