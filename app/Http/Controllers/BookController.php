<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    //
    public function index()
    {
        $books = Book::all();

        return $books;
    }

    public function store(StoreBookRequest $request){
        $requestData = $request->all(['category_id', 'author_id', 'name', 'img', 'description', 'number_available']);

        $requestData['img'] = 'image.jpg';

        Book::create($requestData);

        return "Successfully created book";
    }
}
