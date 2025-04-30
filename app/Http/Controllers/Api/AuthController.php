<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Str;
class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
{

    $user = User::where('email', $request->email)->first();
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
    
    $token = $user->createToken($user->name, ['Auth-Token'])->plainTextToken;
    return response()->json([
        'message' => 'Login successful',
        'token_type' => 'Bearer', 
        'token' => $token
    ], 200);
}

public function register(RegisterRequest $request): JsonResponse
{
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'remember_token' => Str::random(60),
    ]);

    if ($user) {
        $token = $user->createToken($user->name, ['Auth-Token'])->plainTextToken;

        $user->remember_token = $token;
        $user->save();
        
        return response()->json([
            'message' => 'Registration successful',
            'token_type' => 'Bearer',
            'token' => $token,
            'remember_token' => $user->remember_token,
        ], 201);
    } else {
        return response()->json(['message' => 'User not created'], 500);
    }
}

public function profile(Request $request)
{
    $user = $request->user();

    if ($user) {
        return response()->json([
            'message' => 'Profile fetched successfully',
            'data' => $user
        ], 200);
    } else {
        return response()->json([
            'message' => 'Not Authenticated'
        ], 401);
    }
}
public function logout(Request $request){
    $user = User::where('id',$request->user()->id)->first();
    if($user){
        $user->tokens()->delete();
        return response()->json([
            'message' => 'Logged out Successfully',
            'data' => $user
        ], 200);
    }
    else{
        return response()->json([
            'message'=>'User not found'
        ],400);
    }
}
}