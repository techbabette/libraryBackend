<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageTypeStoreRequest;
use App\Models\MessageType;
use Illuminate\Http\Request;

class MessageTypeController extends Controller
{
    public function index(Request $request){
        $perPage = 5;

        $messageTypes = MessageType::query();

        if($request->get('panel')){
            $messageTypes->withCount('messages');
        }

        if($request->get('noPage')){
            $messageTypes = $messageTypes->get();
        }else{
            $messageTypes = $messageTypes->paginate($perPage);
        }

        return response()->json(['message' => 'Successfully fetched message types', "body" => $messageTypes], 201);
    }

    public function edit(MessageTypeEditRequest $request){
        $messageType = MessageType::find($request->id);

        return response()->json(['message' => "Successfully fetched editable information", 'body' => $messageType], 200);
    }

    public function update(MessageTypeUpdateRequest $request){
        $data = $request->all();

        $messageTypeToUpdate = MessageType::find($request->id);
        $messageTypeToUpdate->fill($data);
        $messageTypeToUpdate->save();

        return response()->json(['message' => 'Successfully updated messageType', 'body' => $messageTypeToUpdate], 201);
    }

    public function store(MessageTypeStoreRequest $request){
        $requestData = $request->validated();

        MessageType::create($requestData);

        return response()->json(['message' => 'Successfully created new message type'], 201);
    }
}
