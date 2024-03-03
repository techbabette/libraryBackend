<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageDeleteRequest;
use App\Http\Requests\MessageStoreRequest;
use App\Models\Message;
use App\Models\MessageType;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request){
        $perPage = 5;

        $sortDefault = 'started_at_desc';

        $response = [];
        $response['message'] = 'Successfully fetched messages';
        $response['sortOptions'] = Message::sortOptions();
        $response['sortDefault'] = $sortDefault;

        $messages = Message::query()->with('message_type')->with('user');

        if($request->get('message_types')){
            $messages->whereIn('message_type_id', $request->get('message_types'));
        }

        if($request->get('sortSelected')){
            $messages->sort($request->get('sortSelected'));
        }else{
            $messages->sort($sortDefault);
        }

        if($request->get('noPage')){
            $messages = $messages->get();
        }else{
            $messages = $messages->paginate($perPage);
        }

        $response['body'] = $messages;

        return response()->json($response, 200);
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

    public function delete(MessageDeleteRequest $request){
        $messageId = $request->id;
        $message = Message::find($messageId);

        $message->delete();
        return response()->json(['message' => 'Successfully deleted message'], 200);
    }
}
