<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::has('books')->select(["id", "text"])->withCount('books')->orderBy('books_count', 'desc')->get();

        foreach ($categories as $category){
            $category["text"] = $category['text'] . " (" . $category->books_count.")";
        }

        return $categories;
    }
    public function store(StoreCategoryRequest $request){
        $requestData = $request->validated();

        Category::create($requestData);

        return response()->json(['message' => 'Successfully created new category'], 201);
    }
}
