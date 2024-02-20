<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request){
        $users = User::query();
        $perPage = 5;

        if($request->get('since')){
            $users->where('created_at', '>=', $request->get('since'));
        }

        if($request->get('before')){
            $users->where('created_at', '<=', $request->get('before'));
        }

        if($request->get('onlyCount')){
            $bookCount = $users->count();
            return response()->json(['message' => 'Successfully got user count', 'body' => $bookCount], 200);
        }

        if($request->get('perPage')){
            $perPage = $request->get('perPage');
        }

        $users = $users->paginate($perPage);

        return response()->json(['message' => 'Successfully got user information', 'body' => $users], 200);
    }
}
