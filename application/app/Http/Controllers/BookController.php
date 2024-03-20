<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookDeleteRequest;
use App\Http\Requests\BookEditRequest;
use App\Http\Requests\BookRestoreRequest;
use App\Http\Requests\BookShowRequest;
use App\Http\Requests\BookStoreRequest;
use App\Http\Requests\BookUpdateRequest;
use App\Models\Book;
use function Webmozart\Assert\Tests\StaticAnalysis\integer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        //Early response
        if($request->get('allCount')){
            $bookCount = Book::count();
            return response()->json(['message' => 'Successfully got book count', 'body' => $bookCount], 200);
        }

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

        //Select before sort
        $books->with('author')->with('category');
        $books->withCount('allLoans');
        $books->withCount('loans');

        //Sort
        if($request->get('sortSelected')){
            $books->sort($request->get('sortSelected'));
        }else{
            $books->sort($sortDefault);
        }

        if($request->get('perPage')){
            $perPage = $request->get('perPage');
        }

        if($request->get('noPage')){
            $books = $books->get();
        }else{
            $books = $books->paginate($perPage);
        }

        $response['message'] = 'Successfully fetched books';
        $response['body'] = $books;

        return response()->json($response, 200);
    }

    public function show(BookShowRequest $request){
        $book = Book::withTrashed()->with('author')->with('category');

        $book->withCount('allLoans');
        $book->withCount('loans');
        $book = $book->find($request->id);
        $book['loan_to_user_id'] = false;
        $book['favorite_to_user_id'] = false;

        $loanedToCurrentUser = $book->loanToCurrentUser();
        if($loanedToCurrentUser){
            $book['loan_to_user_id'] = $loanedToCurrentUser->id;
        }

        $favoriteToCurrentUser = $book->favoriteToCurrentUser();
        if($favoriteToCurrentUser){
            $book['favorite_to_user_id'] = $favoriteToCurrentUser->id;
        }

        return response()->json(['message' => 'Successfully fetched book', 'body' => $book], 200);
    }

    public function edit(BookEditRequest $request){
        $book = Book::withTrashed()->find($request->id);

        return response()->json(['message' => "Successfully fetched editable information", 'body' => $book], 200);
    }

    public function update(BookUpdateRequest $request){
        $data = $request->validated();

        if($request->img){
            $image = $request->img;
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $path = $request->img->storeAs('',$imageName);
            $data['img'] = $imageName;
        }

        $bookToUpdate = Book::withTrashed()->find($request->id);
        $bookToUpdate->fill($data);
        $bookToUpdate->save();

        return response()->json(['message' => 'Successfully updated book', 'body' => $bookToUpdate], 200);
    }

    public function store(BookStoreRequest $request){
        $requestData = $request->all(['category_id', 'author_id', 'name', 'img', 'description', 'number_owned']);

        $image = $request->img;
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $path = $request->img->storeAs('',$imageName);
        $requestData['img'] = $imageName;

        $newBookId = Book::create($requestData)->id;
        $response['message'] = 'Successfully created new book';
        $response['body']['book_id'] = $newBookId;

        return response()->json($response, 201);
    }

    public function delete(BookDeleteRequest $request){
        $bookId = $request->id;

        $response = [];

        $book = Book::find($bookId);

        $book->delete();

        return response()->json(['message' => 'Successfully deactivated book'], 200);
    }

    public function restore(BookRestoreRequest $request){
        $bookId = $request->id;
        $book = Book::withTrashed()->find($bookId);
        $book->restore();

        return response()->json(['message' => 'Successfully reactivated book', 200]);
    }
}
