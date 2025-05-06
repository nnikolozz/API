<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
        ]);
        $category = Category::Create([
            'name'=>$request->name,
        ]);
        return response()->json($category, 201);
    }

    
    public function show(string $id)
    {
        $category = Category::Find($id);
        return response()->json($category);
       
    }

   
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=>'required|string|max:255',
        ]);
        $category = Category::findorFail($id);
        $category->update([
            'name'=>$request->name,
        ]);
         return response()->json($category, 200);
    }

    
    public function destroy(string $id)
    {
        $category = Category::findorFail($id);
        $category->delete();
        return response()->json([
            'message'=>'Category deleted successfully',
        ], 200);
    }
}