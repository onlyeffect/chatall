<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['body'];

    protected $appends = ['selfMessage'];
    
    public function getSelfMessageAttribute()
    {
        if (auth()->check()) {
            return $this->user_id === auth()->user()->id;
        } else {
            return false;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
