<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageStoreRequest;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request){
        $perPage = 5;

        $messages = Message::query()->with('message_type')->with('user');

        if($request->get('message_types')){
            $messages->whereIn('message_type_id', $request->get('message_types'));
        }

        if($request->get('noPage')){
            $messages = $messages->get();
        }else{
            $messages = $messages->paginate($perPage);
        }

        return response()->json(['message' => 'Successfully fetched messages', "body" => $messages], 201);
    }

    public function store(MessageStoreRequest $request){
        $messageData = $request->all(["body", "title", "message_type_id"]);
        $userId = auth()->user()->id;

        $messageData->merge([
            "user_id" => $userId
        ]);

        Message::create($messageData);

        return response()->json(['message' => 'Successfully sent new message'], 201);
    }
}
