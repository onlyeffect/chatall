<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;

class CommentsController extends Controller
{
    public function create(Request $request, $id)
    {
        $this->validate($request, [
            'body' => 'required|min:1',
        ]);
            
        Comment::create([
            'post_id' => $id,
            'user_id' => auth()->user()->id,
            'body' => $request->body,
        ]);

        return back();
    }
}
