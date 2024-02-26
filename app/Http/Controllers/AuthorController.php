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

        $response = [];
        $response['sortOptions'] = Author::sortOptions();
        $response['sortDefault'] = $sortDefault;

        if($request->get('currentAndPrevious')){
            $authors->withTrashed();
        }

        if($request->get('previous')){
            $authors->onlyTrashed();
        }

        if($request->get('havingBooks')){
            $authors->has('books');
        }

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
        }

        $response['message'] = 'Successfully retrieved authors';
        $response['body'] = $authors;

        return response()->json($response, 200);
    }
    public function store(AuthorStoreRequest $request){
        $requestData = $request->validated();

        $newAuthorId = Author::create($requestData)->id;
        $response['message'] = 'Successfully created new author';
        $response['body']['author_id'] = $newAuthorId;

        return response()->json($response, 201);
    }
}
