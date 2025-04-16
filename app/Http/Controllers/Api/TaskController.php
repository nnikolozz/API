<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;
class TaskController extends Controller




{
    public function index(Request $request)
{
    $user = $request->user(); 
    $tasks = $user->tasks()->orderBy('due_date')->get(); 

    return response()->json([
        'message' => 'Tasks fetched successfully',
        'data' => $tasks
    ], 200);
    return response()->json([
        'message' => 'Task not found'
    ], 404);
}



public function store (Request $request,Validator $validator){
    $validator = Validator::make($request->all(),[
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'due_date' => 'nullable|date',
        'priority' => 'required|in:low,medium,high',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

$task = Task::create([
    'user_id' => $request->user()->id, 
    'title' => $request->title,
    'description' => $request->description,
    'due_date' => $request->due_date,
    'priority' => $request->priority,
    'status' => 'pending', 
]);
 return response()->json([
        'message' => 'Task created successfully',
        'data' => $task
    ], 201);
}
public function destroy($id, Request $request)
{
    $user = $request->user(); 

    $task = $user->tasks()->find($id);

    if (!$task) {
        return response()->json([
            'message' => 'Task not found'
        ], 404);
    }

    $task->delete();

    return response()->json([
        'message' => 'Task deleted successfully'
    ], 200);
}
}