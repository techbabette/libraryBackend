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
        $perPage = 5;
        $sortDefault = 'books_count_desc';

        if($request->get('havingBooks')){
            $authors->has('books');
        }

        $sortOptions = Author::SortOptons();
        //Sort before retrieval, after filters
        if($request->get('sortSelected')){
            $authors->sort($request->get('sortSelected'));
        }else{
            $authors->sort($sortDefault);
        }

        //Retrieval
        if($request->get('perPage')){
            $perPage = $request->get('perPage');
        }

        if($request->get('noPage')){
            $authors = $authors->get();
        }else{
            $authors = $authors->paginate($perPage);
        }

        //In-memory changes
        if($request->get('bookCountInName')){
            foreach ($authors as $auth){
                $auth["full_name"] = $auth->getFullName() . " (" . $auth->books_count.")";
            }
        }else{
            foreach ($authors as $auth){
                $auth["full_name"] = $auth->getFullName();
            }
        }

        return response()->json(['message' => 'Successfully retrieved authors', 'body' => $authors,
            'sortOptions' => $sortOptions, 'sortDefault' => $sortDefault], 200);
    }
    public function store(AuthorStoreRequest $request){
        $requestData = $request->validated();

        Author::create($requestData);

        return response()->json(['message' => 'Successfully created new author'], 201);
    }
}
