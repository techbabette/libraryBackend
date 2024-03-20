<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorDeleteRequest;
use App\Http\Requests\AuthorEditRequest;
use App\Http\Requests\AuthorRestoreRequest;
use App\Http\Requests\AuthorStoreRequest;
use App\Http\Requests\AuthorUpdateRequest;
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
        $authors->withCount('books');
        $authors->withCount('allBooks');

        if($request->get('withActiveLoanCount') || $request->get('onlyActiveLoanCount')){
            $authors->withCount("activeLoans");
            if($request->get('onlyActiveLoanCount')){
                $authors = $authors->get();
                $response['body'] = $authors;
                return response()->json($response, 200);
            }
        }

        if($request->get('withLoanCount') || $request->get('onlyLoanCount')){
            $authors->withCount("loans");
            if($request->get('onlyLoanCount')){
                $authors = $authors->get();
                $response['body'] = $authors;
                return response()->json($response, 200);
            }
        }

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
            foreach ($authors as $author){
                $newFullName = $author['full_name']. " (".$author->books_count.")";
                $author['full_name_book_count'] = $newFullName;
            }
        }

        if($request->get('statusInName')){
            foreach ($authors as $author){
                $status = $author->deleted_at ? "Inactive" : "Active";
                $newFullName = $author['full_name']. " (".$status.")";
                $author['full_name_status'] = $newFullName;
            }
        }

        $response['message'] = 'Successfully retrieved authors';
        $response['body'] = $authors;

        return response()->json($response, 200);
    }

    public function edit(AuthorEditRequest $request){
        $author = Author::withTrashed()->find($request->id);

        return response()->json(['message' => "Successfully fetched editable information", 'body' => $author], 200);
    }

    public function update(AuthorUpdateRequest $request){
        $data = $request->validated();

        $authorToUpdate = Author::withTrashed()->find($request->id);
        $authorToUpdate->fill($data);
        $authorToUpdate->save();

        return response()->json(['message' => 'Successfully updated author', 'body' => $authorToUpdate], 201);
    }

    public function store(AuthorStoreRequest $request){
        $requestData = $request->validated();

        $newAuthorId = Author::create($requestData)->id;
        $response['message'] = 'Successfully created new author';
        $response['body']['author_id'] = $newAuthorId;

        return response()->json($response, 201);
    }

    public function delete(AuthorDeleteRequest $request){
        $authorId = $request->id;
        $author = Author::find($authorId);
        $author->delete();

        return response()->json(['message' => 'Successfully deactivated author'], 200);
    }

    public function restore(AuthorRestoreRequest $request){
        $authorId = $request->id;
        $author = Author::withTrashed()->find($authorId);
        $author->restore();

        return response()->json(['message' => 'Successfully reactivated author', 200]);
    }
}
