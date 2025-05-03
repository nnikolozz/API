<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;
use App\Http\Requests\TaskRequest;
use App\Models\User;
use App\Models\Category;

class TaskController extends Controller




{
    public function index(Request $request)
{
    $user = $request->user();
    $tasksQuery = Task::query();

    $tasksQuery = Task::with(['category', 'user']);

    if ($request->has('status')) {
        $tasksQuery->where('status', $request->status); 
    }

    if ($request->has('priority')) {
        $tasksQuery->where('priority', $request->priority); 
    }
    

    if ($request->has('due_date')) {
        $tasksQuery->whereDate('due_date', $request->due_date); 
    }
    if( $request->has('title')) {
        $tasksQuery->where('title', $request->title);
    }
    $tasks = $tasksQuery->get();

    if ($tasks->isEmpty()) {
        return response()->json(['message' => 'No tasks found'], 404);
    }

    return response()->json([
        'message' => 'Tasks fetched successfully',
        'data' => $tasks
    ], 200);
}



public function store (TaskRequest $request){

    $user = $request->user();

    $task = Task::create([
    'user_id' => $request->user()->id,
    'title' => $request->title,
    'description' => $request->description,
    'due_date' => $request->due_date,
    'priority' => $request->priority,
    'status' => 'pending',
    'category_id' => $request->category_id,
]);
 return response()->json([
        'message' => 'Task created successfully',
        'data' => $task
    ], 201);
}

public function profile(Request $request)
{
    $user = $request->user();

    if ($user) {

        return response()->json([
            'message' => 'Profile fetched successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'tasks' => $user->tasks
            ]
        ], 200);
    }

    return response()->json(['message' => 'User not found'], 404);
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