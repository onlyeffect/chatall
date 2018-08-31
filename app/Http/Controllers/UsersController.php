<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $activeUsers = $this->user->getActiveUsers();

        return view('users', compact('activeUsers'));
    }
}
