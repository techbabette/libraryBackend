<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookDeleteRequest;
use App\Http\Requests\BookShowRequest;
use App\Http\Requests\BookStoreRequest;
use App\Models\Book;
use function Webmozart\Assert\Tests\StaticAnalysis\integer;
use Illuminate\Http\Request;

class BookController extends Controller
{
    //
    public function index(Request $request)
    {
        $perPage = 3;
        $sortDefault = "created_at_desc";

        $response = [];
        $response['sortOptions'] = Book::sortOptions();
        $response['sortDefault'] = $sortDefault;

        if($request->get('sortOptions')){
            $sortOptions = Book::sortOptions();
            return response()->json(['message' => 'Successfully fetched sort options', 'body' => $sortOptions], 200);
        }

        $books=Book::query();

        //Filters
        if($request->get('currentAndPrevious')){
            $books->withTrashed();
        }

        if($request->get('previous')){
            $books->onlyTrashed();
        }

        if($request->get('title')){
            $books->where('name', 'LIKE', '%'.$request->get('title').'%');
        }

        if($request->get('categories')){
            $books->whereIn('category_id', $request->get('categories'));
        }

        if($request->get('authors')){
            $books->whereIn('author_id', $request->get('authors'));
        }

        //Early response
        if($request->get('onlyCount')){
            $bookCount = Book::count();
            return response()->json(['message' => 'Successfully got book count', 'body' => $bookCount], 200);
        }

        //Select before sort
        $books->with('author')->with('category');

        //Sort
        if($request->get('sortSelected')){
            $books->sort($request->get('sortSelected'));
        }else{
            $books->sort($sortDefault);
        }


        if($request->get('perPage')){
            $perPage = $request->get('perPage');
        }

        $books = $books->paginate($perPage);

        $response['message'] = 'Successfully fetched books';
        $response['body'] = $books;

        return response()->json($response, 200);
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

    public function delete(BookDeleteRequest $request){
        $bookId = $request->id;

        $response = [];

        $book = Book::find($bookId);

        $book->delete();

        return response()->json(['message' => 'Successfully deleted book'], 201);
    }
}
