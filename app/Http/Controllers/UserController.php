<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserShowRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request){
        $users = User::query();
        $perPage = 5;
        $sortDefault = 'created_at_desc';

        $response = [];
        $response['sortOptions'] = User::sortOptions();
        $response['sortDefault'] = $sortDefault;

        if($request->get('allCount')){
            $userCount = User::count();
            return response()->json(['message' => 'Successfully got user count', 'body' => $userCount], 200);
        }

        //Filters
        if($request->get('since')){
            $users->where('created_at', '>=', $request->get('since'));
        }

        if($request->get('before')){
            $users->where('created_at', '<=', $request->get('before'));
        }

        //Sort
        $sortOptions = User::sortOptions();
        //Sort before retrieval, after filters
        if($request->get('sortSelected')){
            $users->sort($request->get('sortSelected'));
        }else{
            $users->sort($sortDefault);
        }

        //Retrieval
        $users->withCount('loans');

        if($request->get('perPage')){
            $perPage = $request->get('perPage');
        }

        if($request->get('noPage')){
            $categories = $users->get();
        }else{
            $categories = $users->paginate($perPage);
        }

        $response['message'] = 'Successfully retrieved users';
        $response['body'] = $categories;

        return response()->json($response, 200);
    }

    public function show(UserShowRequest $request){
        $requestedUserId = $request->route('id');
        $user = User::find($requestedUserId);

        return response()->json(['message' => 'Successfully got user information', 'body' => $user], 200);
    }
}
