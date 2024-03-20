<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LinkPosition;
use Illuminate\Http\Request;

class LinkPositionController extends Controller
{
    public function index(Request $request){
        $perPage = 5;
        $linkPositions = LinkPosition::query();

        $response['message'] = 'Successfully retrieved access levels';

        if($request->get('perPage')){
            $perPage = $request->get('perPage');
        }

        if($request->get('noPage')){
            $linkPositions = $linkPositions->get();
        }else{
            $linkPositions = $linkPositions->paginate($perPage);
        }

        $response['body'] = $linkPositions;

        return response()->json($response, 201);
    }
}
