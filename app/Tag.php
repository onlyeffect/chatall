<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{   
    public function posts(){
        return $this->blongsToMany(Post::class, 'post_tag', 'tag_id', 'post_id');
    }
}
