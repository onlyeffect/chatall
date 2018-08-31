<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    protected $fillable = [
        'title',
        'body',
        'post_image',
        'user_id'
    ]; 

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function deleteWithImage()
    {
        $this->deleteImage();

        $this->delete();
    }

    public function deleteImage()
    {
        if($this->post_image !== 'no_image.jpg') {
            Storage::delete('public/post_images/' . $this->post_image);
        }
    }

    public function addComment($body)
    {
        Comment::create([
            'body' => $body,
            'post_id' => $this->id,
        ]);
    }

    public function incrementViews()
    {
        $this->views = $this->views + 1;
        $this->save();
    }

    public function allWhereHasTag($tagName)
    {
        return self::whereHas('tags', function ($query) use ($tagName){
            $query->where('name', $tagName);
        });
    }

    public function updateTags($tags)
    {
        $this->tags()->detach();
        $this->attachTags($tags);
    }

    public function attachTags($tags)
    {
        $existingTags = Tag::all()->whereIn('name', $tags);
        foreach($tags as $tag){
            $this->attachOrCreateAndAttacht($existingTags, $tag);
        }
    }

    public function attachOrCreateAndAttacht($existingTags, $tag) 
    {
        if($oldTag = $existingTags->firstWhere('name', $tag)){
            $this->tags()->attach($oldTag);
        } else {
            $newTag = new Tag;
            $newTag->name = $tag;
            if($newTag->save()){
                $this->tags()->attach($newTag);
            }
        }
    }
}
