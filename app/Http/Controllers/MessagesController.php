<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Events\MessageCreated;

class MessagesController extends Controller
{
    public function index()
    {
        $messages = Message::with('user')->get();

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);

        $message = $request->user()->messages()->create([
            'body' => $request->body
        ]);

        broadcast(new MessageCreated($message))->toOthers();
   
        return response()->json($message);
    }
}
