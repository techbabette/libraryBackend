<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request){
        $categories = Category::query();

        if($request->get('withLoanCount') || $request->get('onlyLoanCount')){
            $categories->withCount("loans")->having("loans_count" , '>', 0);
        }

        if($request->get('onlyLoanCount')){
            return $categories->get();
        }

        if($request->get('onlyCount')){
            return $categories->count();
        }

        $categories = $categories->has('books')->withCount('books')->orderBy('books_count', 'desc')->get();

        if($request->get('bookCountInName')){
            foreach ($categories as $category){
                $category["text"] = $category['text'] . " (" . $category->books_count.")";
            }
        }

        return $categories;
    }
    public function store(CategoryStoreRequest $request){
        $requestData = $request->validated();

        Category::create($requestData);

        return response()->json(['message' => 'Successfully created new category'], 201);
    }
}
