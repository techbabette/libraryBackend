<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    //
    public function index(Request $request)
    {
        $perPage = 3;

        if($request->get('perPage')){
            $perPage = $request->get('perPage');
        }

        $books = Book::with('author')->with('category');

        if($request->get('categories')){
            $books->whereIn('category_id', $request->get('categories'));
        }

        if($request->get('authors')){
            $books->whereIn('author_id', $request->get('authors'));
        }

        $books = $books->paginate($perPage);

        return response()->json(['message' => 'Successfully fetched books', 'body' => $books], 201);
    }

    public function store(StoreBookRequest $request){
        $requestData = $request->all(['category_id', 'author_id', 'name', 'img', 'description', 'number_available']);

        $requestData['img'] = 'image.jpg';

        Book::create($requestData);

        return response()->json(['message' => 'Successfully created new book'], 201);
    }
}
