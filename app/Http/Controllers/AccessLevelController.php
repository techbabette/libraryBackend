<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccessLevel;
use Illuminate\Http\Request;

class AccessLevelController extends Controller
{
    public function index(Request $request){
        $perPage = 5;
        $accessLevels = AccessLevel::query();

        $response['message'] = 'Successfully retrieved access levels';

        if($request->get('perPage')){
            $perPage = $request->get('perPage');
        }

        if($request->get('noPage')){
            $accessLevels = $accessLevels->get();
        }else{
            $accessLevels = $accessLevels->paginate($perPage);
        }

        $response['body'] = $accessLevels;

        return response()->json($response, 201);
    }
}
