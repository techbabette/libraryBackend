<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageTypeDeleteRequest;
use App\Http\Requests\MessageTypeEditRequest;
use App\Http\Requests\MessageTypeStoreRequest;
use App\Http\Requests\MessageTypeUpdateRequest;
use App\Models\MessageType;
use Illuminate\Http\Request;

class MessageTypeController extends Controller
{
    public function index(Request $request){
        $perPage = 5;
        $sortDefault = "created_at_desc";

        $response = [];
        $response['message'] = 'Successfully fetched message types';

        $messageTypes = MessageType::query();

        if($request->get('panel')){
            $response['sortOptions'] = MessageType::sortOptions();
            $response['sortDefault'] = $sortDefault;

            if($request->get('sortSelected')){
                $messageTypes->sort($request->get('sortSelected'));
            }else{
                $messageTypes->sort($sortDefault);
            }
            $messageTypes->withCount('messages');
        }

        if($request->get('noPage')){
            $messageTypes = $messageTypes->get();
        }else{
            $messageTypes = $messageTypes->paginate($perPage);
        }

        $response['body'] = $messageTypes;

        return response()->json($response, 200);
    }

    public function edit(MessageTypeEditRequest $request){
        $messageType = MessageType::find($request->id);

        return response()->json(['message' => "Successfully fetched editable information", 'body' => $messageType], 200);
    }

    public function update(MessageTypeUpdateRequest $request){
        $data = $request->validated();

        $messageTypeToUpdate = MessageType::find($request->id);
        $messageTypeToUpdate->fill($data);
        $messageTypeToUpdate->save();

        return response()->json(['message' => 'Successfully updated message type', 'body' => $messageTypeToUpdate], 201);
    }

    public function store(MessageTypeStoreRequest $request){
        $requestData = $request->validated();

        MessageType::create($requestData);

        return response()->json(['message' => 'Successfully created new message type'], 201);
    }

    public function delete(MessageTypeDeleteRequest $request){
        $messageTypeId = $request->id;
        $messageType = MessageType::find($messageTypeId);

        $messageType->delete();
        return response()->json(['message' => 'Successfully deleted message type'], 200);
    }
}
