<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Post;
use App\Tag;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::withCount('posts')->orderBy('posts_count', 'desc')->get();

        return view('users')->with('users', $users);
    }
}
