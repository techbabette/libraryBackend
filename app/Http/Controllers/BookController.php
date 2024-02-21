<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookShowRequest;
use App\Http\Requests\BookStoreRequest;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    //
    public function index(Request $request)
    {
        $perPage = 3;
        $sortDefault = "created_at_desc";

        if($request->get('sortOptions')){
            $sortOptions = Book::sortOptions();
            return response()->json(['message' => 'Successfully fetched sort options', 'body' => $sortOptions], 200);
        }

        if($request->get('perPage')){
            $perPage = $request->get('perPage');
        }

        $books = Book::with('author')->with('category');

        if($request->get('title')){
            $books->where('name', 'LIKE', '%'.$request->get('title').'%');
        }

        if($request->get('categories')){
            $books->whereIn('category_id', $request->get('categories'));
        }

        if($request->get('authors')){
            $books->whereIn('author_id', $request->get('authors'));
        }

        if($request->get('onlyCount')){
            $bookCount = Book::count();
            return response()->json(['message' => 'Successfully got book count', 'body' => $bookCount], 200);
        }

        if($request->get('sortSelected')){
            $books->sort($request->get('sortSelected'));
        }else{
            $books->sort($sortDefault);
        }

        $sortOptions = Book::sortOptions();
        $books = $books->paginate($perPage);

        return response()->json(
            [
            'message' => 'Successfully fetched books',
            'body' => $books,
            'sortOptions' => $sortOptions,
            'sortDefault' => $sortDefault
            ], 200);
    }

    public function show(BookShowRequest $request){
        $book = Book::with('author')->with('category')->find($request->id);

        $book['total_loans'] = $book->loanTotalCount();
        $book['current_loans'] = $book->loansCurrentCount();
        $book['loan_id'] = $book->loanedToCurrentUser();

        $book['currently_available'] = $book->number_owned - $book->current_loans;

        return response()->json(['message' => 'Successfully fetched book', 'body' => $book], 200);
    }

    public function store(BookStoreRequest $request){
        $requestData = $request->all(['category_id', 'author_id', 'name', 'img', 'description', 'number_owned']);

        $requestData['img'] = 'image.jpg';

        Book::create($requestData);

        return response()->json(['message' => 'Successfully created new book'], 201);
    }
}
