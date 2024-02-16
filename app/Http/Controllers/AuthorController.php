<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthorRequest;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    //
    public function index()
    {
        $authors = Author::all(["id", "name", "last_name"]);

        foreach ($authors as $auth){
            $auth["full_name"] = $auth->getFullName();
        }

        return $authors;
    }
    public function store(StoreAuthorRequest $request){
        $requestData = $request->validated();

        Author::create($requestData);

        return response()->json(['message' => 'Successfully created new author'], 201);
    }
}
