<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LinkEditRequest;
use App\Http\Requests\LinkStoreRequest;
use App\Http\Requests\LinkUpdateRequest;
use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 5;
        $links = Link::query();
        $sortDefault = "created_at_desc";

        $response['message'] = 'Successfully retrieved links';
        $response['sortDefault'] = $sortDefault;

        $links->with("access_level")->with('link_position');

        if($request->get('perPage')){
            $perPage = $request->get('perPage');
        }

        if($request->get('noPage')){
            $links = $links->get();
        }else{
            $links = $links->paginate($perPage);
        }

        $response['body'] = $links;

        return response()->json($response, 201);
    }

    public function me(){
        $userAccessLevel = -1;
        if(auth()->user()){
            $userAccessLevel = auth()->user()->access_level->access_level;
        }

        $links = Link::getLinksForAccessLevel($userAccessLevel);

        return response($links);
    }

    public function edit(LinkEditRequest $request){
        $link = Link::find($request->id);

        return response()->json(['message' => "Successfully fetched editable information", 'body' => $link], 200);
    }

    public function update(LinkUpdateRequest $request){
        $data = $request->all();

        $linkToUpdate = Link::find($request->id);
        $linkToUpdate->fill($data);
        $linkToUpdate->save();

        return response()->json(['message' => 'Successfully updated link', 'body' => $linkToUpdate], 201);
    }

    public function store(LinkStoreRequest $request){
        $requestData = $request->validated();

        $newLinkId = Link::create($requestData)->id;
        $response['message'] = 'Successfully created new link';
        $response['body']['link_id'] = $newLinkId;

        return response()->json($response, 201);
    }
}
