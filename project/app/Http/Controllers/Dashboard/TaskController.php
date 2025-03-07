<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $records = Task::query()->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('task.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('task.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            Task::create([
                'title'       => $request->title,
                'description' => $request->description,
                'user_id'     => auth()->id()
            ]);

            return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the task.')
                ->exceptInput('_token');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $record = Task::findOrFail($id);
            $this->authorize('update', $record);

            return view('task.edit', compact('record'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Task not found.')->exceptInput('_token');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:pending,in_progress,completed'
        ]);
        try {
            $task = Task::findOrFail($id);
            $this->authorize('update', $task);

            Task::query()->where('id', $task->id)->update([
                'title'       => $request->title,
                'description' => $request->description,
                'status'      => $request->status
            ]);
            return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the task.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $task = Task::findOrFail($id);
            $this->authorize('delete', $task);

            $task->delete();

            return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the task.');
        }
    }

    /**
     * @param  Request  $request
     * @param  Task  $task
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateStatus(Request $request, Task $task): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        try {
            $task->update(['status' => $validated['status']]);
            return redirect()->route('tasks.index')->with('success', 'Task status updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating task status.');
        }
    }

    /**
     * @param  Task  $task
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function complete(Task $task): \Illuminate\Http\RedirectResponse
    {
        return $this->updateStatus(new Request(['status' => 'completed']), $task);
    }

    /**
     * @param  Task  $task
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function markInProgress(Task $task): \Illuminate\Http\RedirectResponse
    {
        return $this->updateStatus(new Request(['status' => 'in_progress']), $task);
    }
}
