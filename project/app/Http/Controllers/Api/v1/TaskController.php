<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Throwable;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $tasks = Task::query()->where('user_id', auth()->id())->paginate();
            return response()->json(TaskResource::collection($tasks));
        } catch (Throwable $e) {
            return response()->json(['error' => 'Failed to fetch tasks'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validated = $request->validate([
                'title'       => 'required|string|max:255',
                'description' => 'nullable|string',
                'status'      => 'required|in:pending,in_progress,completed'
            ]);

            $task = Task::create(array_merge($validated, ['user_id' => auth()->id()]));

            return response()->json(new TaskResource($task), 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Failed to create task'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): \Illuminate\Http\JsonResponse
    {
        try {
            $this->authorize('view', $task);

            return response()->json(new TaskResource($task));
        } catch (ModelNotFoundException $e) {
            return response()->json(null, 204);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Failed to fetch task'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task): \Illuminate\Http\JsonResponse
    {
        try {
            $this->authorize('update', $task);

            $validated = $request->validate([
                'title'       => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'status'      => 'sometimes|in:pending,in_progress,completed'
            ]);

            $task->update($validated);
            return response()->json(new TaskResource($task));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Failed to update task'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): \Illuminate\Http\JsonResponse
    {
        try {
            $this->authorize('delete', $task);

            $task->delete();

            return response()->json(['message' => 'Task deleted']);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Failed to delete task'], 500);
        }
    }
}
