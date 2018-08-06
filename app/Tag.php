<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{   
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public static function reviseTags()
    {
        $tags = Tag::with('posts')->get();
        foreach($tags as $tag){
            if ($tag->posts->count() <= 0) {
                $tag->delete();
            }
        }
    }
}
