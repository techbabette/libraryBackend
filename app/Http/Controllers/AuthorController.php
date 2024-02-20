<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorStoreRequest;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    //
    public function index(Request $request){
        $authors = Author::query();

        if($request->get('havingBooks')){
            $authors->has('books');
        }

        $authors = $authors->select('id', 'name', 'last_name')->withCount('books')->orderBy('books_count', 'desc')->get();

        if($request->get('bookCountInName')){
            foreach ($authors as $auth){
                $auth["full_name"] = $auth->getFullName() . " (" . $auth->books_count.")";
            }
        }else{
            foreach ($authors as $auth){
                $auth["full_name"] = $auth->getFullName();
            }
        }

        return response()->json(['message' => 'Successfully retrieved authors', 'body' => $authors], 200);
    }
    public function store(AuthorStoreRequest $request){
        $requestData = $request->validated();

        Author::create($requestData);

        return response()->json(['message' => 'Successfully created new author'], 201);
    }
}
