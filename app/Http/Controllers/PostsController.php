<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Tag;
use App\ImageUploader;
use Purifier;

class PostsController extends Controller
{
    private $post;

    private $tag;

    private $imageUploader;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
     public function __construct(Post $post, Tag $tag, ImageUploader $imageUploader)
     {
         $this->post = $post;
         $this->tag = $tag;
         $this->imageUploader = $imageUploader;
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
        $tagName = $request['tag'];

        if (! empty($tagName)) {
            $posts = $this->post->allWhereHasTag($tagName)->orderBy('created_at', 'desc')->paginate(3);
        } else {
            $posts = $this->post->with('tags')->orderBy('created_at', 'desc')->paginate(3);
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
        $tags = $this->tag->all();
        
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

        if ($request->hasFile('post_image')) {
            $filenameToStore = $this->imageUploader->upload($request['post_image']);
        } else {
            $filenameToStore = 'no_image.jpg';
        }

        $post = $this->post->create([
            'title' => $request['title'],
            'body' => Purifier::clean($request['body']),
            'post_image' => $filenameToStore,
            'user_id' => auth()->user()->id
        ]);

        $post->attachTags($request['tags']);

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
        if($post = $this->post->find($id)){
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
        if($post = $this->post->find($id)){
            // Checking for correct user
            if($post->user_id !== auth()->user()->id && !auth()->user()->isAdmin){
                return redirect('/posts')->with('error', 'Unauthorized page!');
            }

            $postTags = $post->tags->pluck('name');

            $freeTags = $this->tag->all()->whereNotIn('name', $postTags);

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
        
        if($post = $this->post->find($id)){
            $post->title = $request['title'];
            $post->body = Purifier::clean($request['body']);

            if($request->hasFile('post_image')){
                $post->deleteImage();

                $filenameToStore = $this->imageUploader->upload($request['post_image']);

                $post->post_image = $filenameToStore;
            }
            
            $post->updateTags($request['tags']);
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
        if($post = $this->post->find($id)){
            // Checking for correct user
            if($post->user_id !== auth()->user()->id && !auth()->user()->isAdmin){
                return redirect('/posts')->with('error', 'Unauthorized page!');
            }

            $post->deleteWithImage();

            return redirect('/posts')->with('success', 'Post Deleted');
        } else {
            return redirect('/posts')->with('error', 'Post not found.');
        }
    }
}
