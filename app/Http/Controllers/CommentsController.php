<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Post;
use App\Comment;
// use App\User;

class CommentsController extends Controller
{
    public function create(Request $request, $id){
        $this->validate($request, [
            'body' => 'required|min:1',
        ]);
            
        Comment::create([
            'post_id' => $id,
            'user_id' => auth()->user()->id,
            'body' => $request->body,
        ]);

        // $post->addComment(request('body'));

        // $user = User::find(request('user_id'));

        // $user->addComment(request('body'));

        return back();
    }
}
