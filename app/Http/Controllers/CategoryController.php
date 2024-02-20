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

        if($request->get('havingBooks')){
            $categories->has('books');
        }

        if($request->get('havingActiveLoans')){
            $categories->has("activeLoans");
        }

        if($request->get('havingLoans')){
            $categories->has("loans");
        }

        if($request->get('withActiveLoanCount') || $request->get('onlyActiveLoanCount')){
            $categories->withCount("activeLoans");
            if($request->get('onlyActiveLoanCount')){
                $categories = $categories->get();
                return response()->json(['message' => 'Successfully retrieved categories', 'body' => $categories], 200);
            }
        }

        if($request->get('withLateLoanCount') || $request->get('onlyLateLoanCount')){
            $categories->withCount("lateLoans");
            if($request->get('onlyLateLoanCount')){
                $categories = $categories->get();
                return response()->json(['message' => 'Successfully retrieved categories', 'body' => $categories], 200);
            }
        }

        if($request->get('withNewLoanCount') || $request->get('onlyNewLoanCount')){
            $categories->withCount("newLoans");
            if($request->get('onlyNewLoanCount')){
                $categories = $categories->get();
                return response()->json(['message' => 'Successfully retrieved categories', 'body' => $categories], 200);
            }
        }

        if($request->get('withLoanCount') || $request->get('onlyLoanCount')){
            $categories->withCount("loans");
            if($request->get('onlyLoanCount')){
                $categories = $categories->get();
                return response()->json(['message' => 'Successfully retrieved categories', 'body' => $categories], 200);
            }
        }

        if($request->get('onlyCount')){
            $categories = $categories->get();
            return response()->json(['message' => 'Successfully retrieved categories', 'body' => $categories], 200);
        }

        $categories = $categories->withCount('books')->orderBy('books_count', 'desc')->get();

        if($request->get('bookCountInName')){
            foreach ($categories as $category){
                $category["text"] = $category['text'] . " (" . $category->books_count.")";
            }
        }

        return response()->json(['message' => 'Successfully retrieved categories', 'body' => $categories], 200);
    }
    public function store(CategoryStoreRequest $request){
        $requestData = $request->validated();

        Category::create($requestData);

        return response()->json(['message' => 'Successfully created new category'], 201);
    }
}
