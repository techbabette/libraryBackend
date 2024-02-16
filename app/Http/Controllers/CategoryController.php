<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        return Category::all(['id', 'text']);
    }
    public function store(StoreCategoryRequest $request){
        $requestData = $request->validated();

        Category::create($requestData);

        return response()->json(['message' => 'Successfully created new category'], 201);
    }
}
