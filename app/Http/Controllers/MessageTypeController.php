<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageTypeStoreRequest;
use App\Models\MessageType;
use Illuminate\Http\Request;

class MessageTypeController extends Controller
{
    public function index(){
        $messageTypes = MessageType::get(["id", "name"]);

        return $messageTypes;
    }
    public function store(MessageTypeStoreRequest $request){
        $requestData = $request->validated();

        MessageType::create($requestData);

        return response()->json(['message' => 'Successfully created new message type'], 201);
    }
}
