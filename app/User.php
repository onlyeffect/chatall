<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function canDelete(Post $post)
    {
        return $post->user_id === $this->id || $this->isAdmin;
    }

    public function canEdit(Post $post)
    {
        return $post->user_id === $this->id || $this->isAdmin;
    }

    public function getTags()
    {
        $userTags = Tag::whereHas('posts.user', function ($query) {
            $query->where('id', $this->id);
        })->get();
        
        return $userTags;
    }

    public function createMessage(string $messageBody)
    {
        $message = $this->messages()->create([
            'body' => $messageBody
        ]);
        
        return $message;
    }

    public function getActiveUsers()
    {
        return self::withCount('posts')->orderBy('posts_count', 'desc')->get();
    }
}
