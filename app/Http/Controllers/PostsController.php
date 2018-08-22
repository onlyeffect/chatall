<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;
use App\Tag;
use Purifier;

class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
     public function __construct()
     {
        //  $this->middleware('preventBackHistory');
         $this->middleware('auth')->except(['index', 'show']);
     }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($tagName = $request['tag']) {
            $posts = Post::allWhereHasTag($tagName)->orderBy('created_at', 'desc')->paginate(3);
        } else {
            $posts = Post::with('tags')->orderBy('created_at', 'desc')->paginate(3);
        }

        return view('posts.index', compact('posts', 'tagName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::all();
        
        return view('posts.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'tags' => 'required',
            'post_image' => 'image|nullable|max:1999',
        ]);

        // Handle image uploading
        $filenameToStore = ($request->hasFile('post_image')) ? $this->saveImage($request->file('post_image')) : 'no_image.jpg';

        $post = new Post;
        $post->title = $request['title'];
        $post->body = Purifier::clean($request['body']);
        $post->post_image = $filenameToStore;
        $post->user_id = auth()->user()->id;
        $post->save();

        $this->attachAllTags($post, $request['tags']);

        return redirect('/posts')->with('success', 'Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($post = Post::find($id)){
            $post->incrementViews();
            $comments = $post->comments->sortByDesc('created_at');
            return view('posts.show', compact('post', 'comments'));
        } else {
            return redirect('/posts')->with('error', 'Post not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if($post = Post::find($id)){
            // Checking for correct user
            if($post->user_id !== auth()->user()->id && !auth()->user()->isAdmin){
                return redirect('/posts')->with('error', 'Unauthorized page!');
            }
            $postTags = $post->tags->pluck('name');
            $freeTags = Tag::all()->whereNotIn('name', $postTags);
            return view('posts.edit', compact('post', 'freeTags'));
        } else {
            return redirect('/posts')->with('error', 'Post not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'tags' => 'required',
            'post_image' => 'image|nullable|max:1999'
        ]);
        
        if($post = Post::find($id)){
            $post->title = $request['title'];
            $post->body = Purifier::clean($request['body']);
            if($request->hasFile('post_image')){
                // Deleting previous image
                if($post->post_image !== 'no_image.jpg') {
                    Storage::delete('public/post_images/'.$post->post_image);
                }
                $filenameToStore = $this->saveImage($request['post_image']);
                $post->post_image = $filenameToStore;
            }
            $post->tags()->detach();
            $this->attachAllTags($post, $request['tags']);
            $post->save();
            return redirect('/posts')->with('success', 'Post Updated');
        } else {
            return redirect('/posts')->with('error', 'Post not found.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($post = Post::find($id)){
            // Checking for correct user
            if($post->user_id !== auth()->user()->id && !auth()->user()->isAdmin){
                return redirect('/posts')->with('error', 'Unauthorized page!');
            }
            if($post->post_image !== 'no_image.jpg'){
                Storage::delete('public/post_images/'.$post->post_image);
            }
            $post->delete();
            return redirect('/posts')->with('success', 'Post Deleted');
        } else {
            return redirect('/posts')->with('error', 'Post not found.');
        }
    }

    private function saveImage($image){
        $filenameWithExt = $image->getClientOriginalName();
        $fileExt = $image->getClientOriginalExtension();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        if(
            $fileExt !== 'jpg' && 
            $fileExt !== 'jpeg' && 
            $fileExt !== 'gif' && 
            $fileExt !== 'bmp' && 
            $fileExt !== 'png' && 
            $fileExt !== 'svg'
        ){
            return redirect('/posts/create')->with('error', 'Invalid file extension');
        }
        $filenameToStore = $filename . '_' . time() . '.' . $fileExt;
        if($image->storeAs('public/post_images', $filenameToStore)){
            return $filenameToStore;
        }
    }

    private function attachAllTags(Post $post, $tags)
    {
        $existingTags = Tag::all()->whereIn('name', $tags);
        foreach($tags as $tag){
            $post->attachOrCreateAndAttachtTag($existingTags, $tag);
        }
    }
}
