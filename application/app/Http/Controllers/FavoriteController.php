<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\FavoriteDeleteRequest;
use App\Http\Requests\FavoriteStoreRequest;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request){
        $perPage = 5;
        $sortDefault = 'created_at_desc';

        $response = [];
        $response['sortOptions'] = Favorite::sortOptions();
        $response['sortDefault'] = $sortDefault;

        if($request->get('sortOptions')){
            $response['message'] = "Successfully fetched sort options";
            return response()->json($response, 200);
        }

        $favorites = Favorite::query()->with(['book' => function ($query) {
            return $query->withTrashed();
        }]);

        //Filters
        
        //Limit response for base case
        if(!$request->get('panel')){
            $userId = auth()->user()->id;
            $favorites->where('user_id', '=', $userId);
            $response['message'] = 'Successfully retrieved user favorites';
        }

        if($request->get('since')){
            $favorites->where('created_at', '>=', $request->get('since'));
        }

        if($request->get('before')){
            $favorites->where('created_at', '<=', $request->get('before'));
        }

        if($request->get('panel')){
            $favorites = $favorites->with('user');
            $response['message'] = 'Successfully retrieved all favorites';
        }

        if($request->get('sortSelected')){
            $favorites->sort($request->get('sortSelected'));
        }else{
            $favorites->sort($sortDefault);
        }

        if($request->get('perPage')){
            $perPage = $request->get('perPage');
        }

        if($request->get('noPage')){
            $favorites = $favorites->get();
        }else{
            $favorites = $favorites->paginate($perPage);
        }

        $response['body'] = $favorites;
        return response()->json($response, 200);
    }

    public function store(FavoriteStoreRequest $request){
        $bookId = $request->book_id;
        $userId = auth()->user()->id;

        $favoriteId = Favorite::create(['user_id' => $userId, 'book_id' => $bookId])->id;
        $response['message'] = "Successfully added book to favorites";
        $response['body']['favorite_id'] = $favoriteId;
        return response()->json($response, 200);
    }

    public function delete(FavoriteDeleteRequest $request){
        $favoriteId = $request->id;

        $favorite = Favorite::find($favoriteId);
        $favorite->delete();

        $response['message'] = 'Successfully removed book from favorites';
        return response()->json($response);
    }
}
