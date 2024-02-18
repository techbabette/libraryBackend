<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request){
        if($request->get('onlyCount')){
            $bookCount = User::count();
            return response()->json(['message' => 'Successfully got user count', 'body' => $bookCount], 200);
        }
    }
}
