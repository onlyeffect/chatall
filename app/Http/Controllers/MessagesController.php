<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Events\MessageCreated;

class MessagesController extends Controller
{
    private $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function index()
    {
        $messages = $this->message->with('user')->orderBy('created_at', 'asc')->paginate(10);

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);
        
        $message = $request->user()->createMessage($request->body);

        broadcast(new MessageCreated($message))->toOthers();
   
        return response()->json($message);
    }
}
