<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LinkStoreRequest;
use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index()
    {
        $userAccessLevel = -1;
        if(auth()->user()){
            $userAccessLevel = auth()->user()->access_level->access_level;
        }

        $links = Link::getLinksForAccessLevel($userAccessLevel);

        return response($links);
    }

    public function store(LinkStoreRequest $request){
        $requestData = $request->validated();

        $newLinkId = Link::create($requestData)->id;
        $response['message'] = 'Successfully created new link';
        $response['body']['link_id'] = $newLinkId;

        return response()->json($response, 201);
    }
}
