<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryDeleteRequest;
use App\Http\Requests\CategoryEditRequest;
use App\Http\Requests\CategoryRestoreRequest;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request){
        $categories = Category::query();
        $perPage = 5;
        $sortDefault = 'books_count_desc';

        $response = [];
        $response['message'] = 'Successfully retrieved categories';
        $response['sortOptions'] = Category::sortOptions();
        $response['sortDefault'] = $sortDefault;

        if($request->get('currentAndPrevious')){
            $categories->withTrashed();
        }

        if($request->get('previous')){
            $categories->onlyTrashed();
        }

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
                $response['body'] = $categories;
                return response()->json($response, 200);
            }
        }

        if($request->get('withLateLoanCount') || $request->get('onlyLateLoanCount')){
            $categories->withCount("lateLoans");
            if($request->get('onlyLateLoanCount')){
                $categories = $categories->get();
                $response['body'] = $categories;
                return response()->json($response, 200);
            }
        }

        if($request->get('withNewLoanCount') || $request->get('onlyNewLoanCount')){
            $categories->withCount("newLoans");
            if($request->get('onlyNewLoanCount')){
                $categories = $categories->get();
                $response['body'] = $categories;
                return response()->json($response, 200);
            }
        }

        if($request->get('withLoanCount') || $request->get('onlyLoanCount')){
            $categories->withCount("loans");
            if($request->get('onlyLoanCount')){
                $categories = $categories->get();
                $response['body'] = $categories;
                return response()->json($response, 200);
            }
        }

        if($request->get('onlyCount')){
            $categories = $categories->get();
            $response['body'] = $categories;
            return response()->json($response, 200);
        }

        $categories->withCount('books');
        $categories->withCount('allBooks');

        $sortOptions = Category::sortOptions();
        //Sort before retrieval, after filters
        if($request->get('sortSelected')){
            $categories->sort($request->get('sortSelected'));
        }else{
            $categories->sort($sortDefault);
        }

        if($request->get('perPage')){
            $perPage = $request->get('perPage');
        }

        if($request->get('noPage')){
            $categories = $categories->get();
        }else{
            $categories = $categories->paginate($perPage);
        }

        if($request->get('bookCountInName')){
            foreach ($categories as $category){
                $category["text"] = $category['text'] . " (" . $category->books_count.")";
            }
        }

        if($request->get('statusInName')){
            foreach ($categories as $category){
                $status = $category->deleted_at ? "Inactive" : "Active";
                $category['text'] = $category['text']. " (".$status.")";
            }
        }

        $response['body'] = $categories;
        return response()->json($response, 200);
    }

    public function edit(CategoryEditRequest $request){
        $category = Category::withTrashed()->find($request->id);

        return response()->json(['message' => "Successfully fetched editable information", 'body' => $category], 200);
    }

    public function update(CategoryUpdateRequest $request){
        $data = $request->validated();

        $categoryToUpdate = Category::withTrashed()->find($request->id);
        $categoryToUpdate->fill($data);
        $categoryToUpdate->save();

        return response()->json(['message' => 'Successfully updated category', 'body' => $categoryToUpdate], 201);
    }

    public function store(CategoryStoreRequest $request){
        $requestData = $request->validated();

        $newCategoryId = Category::create($requestData)->id;
        $response['message'] = 'Successfully created new category';
        $response['body']['category_id'] = $newCategoryId;

        return response()->json($response, 201);
    }

    public function delete(CategoryDeleteRequest $request){
        $categoryId = $request->id;
        $category = Category::find($categoryId);
        $category->delete();

        return response()->json(['message' => 'Successfully deactivated category'], 200);
    }

    public function restore(CategoryRestoreRequest $request){
        $categoryId = $request->id;
        $category = Category::withTrashed()->find($categoryId);
        $category->restore();

        return response()->json(['message' => 'Successfully reactivated category', 200]);
    }
}
