<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageStoreRequest;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(){

    }

    public function store(MessageStoreRequest $request){
        $messageData = $request->all(["body", "title", "message_type_id"]);

        Message::create($messageData);

        return response()->json(['message' => 'Successfully sent new message'], 201);
    }
}
