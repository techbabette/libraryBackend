<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LinkDeleteRequest;
use App\Http\Requests\LinkEditRequest;
use App\Http\Requests\LinkStoreRequest;
use App\Http\Requests\LinkUpdateRequest;
use App\Models\Link;
use App\Models\MessageType;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 5;
        $links = Link::query();
        $sortDefault = "created_at_desc";

        $response['message'] = 'Successfully retrieved links';
        $response['sortOptions'] = Link::sortOptions();
        $response['sortDefault'] = $sortDefault;

        $links->with("access_level")->with('link_position');

        if($request->get('sortSelected')){
            $links->sort($request->get('sortSelected'));
        }else{
            $links->sort($sortDefault);
        }

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

        $data['links'] = Link::getLinksForAccessLevel($userAccessLevel);
        $data['access_level'] = $userAccessLevel;

        return response($data);
    }

    public function everyone(){
        $userAccessLevel = -1;
        $accessLevelEveryone = 0;
        if(auth()->user()){
            $userAccessLevel = auth()->user()->access_level->access_level;
        }

        $data['links'] = Link::getLinksForAccessLevel($accessLevelEveryone);
        $data['access_level'] = $userAccessLevel;

        return response($data);
    }

    public function edit(LinkEditRequest $request){
        $link = Link::find($request->id);

        return response()->json(['message' => "Successfully fetched editable information", 'body' => $link], 200);
    }

    public function update(LinkUpdateRequest $request){
        $data = $request->validated();

        $linkToUpdate = Link::find($request->id);
        $linkToUpdate->fill($data);
        $linkToUpdate->save();

        return response()->json(['message' => 'Successfully updated link', 'body' => $linkToUpdate], 200);
    }

    public function store(LinkStoreRequest $request){
        $requestData = $request->validated();

        $newLinkId = Link::create($requestData)->id;
        $response['message'] = 'Successfully created new link';
        $response['body']['link_id'] = $newLinkId;

        return response()->json($response, 201);
    }

    public function delete(LinkDeleteRequest $request){
        $linkId = $request->id;
        $link = Link::find($linkId);

        $link->delete();
        return response()->json(['message' => 'Successfully deleted link'], 200);
    }
}
