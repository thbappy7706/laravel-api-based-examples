<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success(TaskResource::collection(Task::all()), 'Tasks retrieved successfully');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create( )
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {

        $task = Task::create($request->validated());
        return $this->created(new TaskResource($task), 'Task created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task = Task::find($task);
        return $this->success(new TaskResource($task), 'Task fetched successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, string $id)
    {
        $task = Task::find($id);
        $task->update($request->validated());
        return $this->success(new TaskResource($task), 'Task updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);
        $task->delete();
        return $this->success(message: 'Task deleted successfully');
    }
}
