<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthorRequest;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    //
    public function index(){
        $authors = Author::has('books')->select('name', 'last_name')->withCount('books')->orderBy('books_count', 'desc')->get();

        foreach ($authors as $auth){
            $auth["full_name"] = $auth->getFullName() . " (" . $auth->books_count.")";
        }

        return $authors;
    }
    public function store(StoreAuthorRequest $request){
        $requestData = $request->validated();

        Author::create($requestData);

        return response()->json(['message' => 'Successfully created new author'], 201);
    }
}
