<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// Используем модель
use App\Post;
use App\Tag;

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
        if($request['tag']){
            $posts = Post::whereHas('tags', function ($query) use ($request){
                $query->where('name', $request['tag']);
            })
                ->orderBy('created_at', 'desc')
                ->paginate(3);
        } else {
            $posts = Post::with('tags')->orderBy('created_at', 'desc')->paginate(3);
        }

        return view('posts.index')->with('posts', $posts);
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
        $filenameToStore = ($request->hasFile('post_image')) ? $this->uploadFile($request) : 'no_image.jpg';

        $post = new Post;
        $post->title = $request['title'];
        $post->body = $request['body'];
        $post->post_image = $filenameToStore;
        $post->user_id = auth()->user()->id;
        $post->save();

        $existingTags = Tag::all()->whereIn('name', $request['tags']);

        foreach($request['tags'] as $tag){
            if($oldTag = $existingTags->firstWhere('name', $tag)){
                $post->tags()->attach($oldTag);
            } else {
                $newTag = new Tag;
                $newTag->name = $tag;
                if($newTag->save()){
                    $post->tags()->attach($newTag);
                }
            }
        }
        
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
            $comments = $post->comments;
            $comments = $comments->sortByDesc('created_at');
            return view('posts.show', compact('post', 'comments'));
        }else{
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
            if($post->user_id !== auth()->user()->id)
                return redirect('/posts')->with('error', 'Unauthorized page!');

                $postTagNames = $post->tags->pluck('name');
                $freeTags = Tag::all()->whereNotIn('name', $postTagNames);

            return view('posts.edit', compact('post', 'freeTags'));
        }else {
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
            $post->body = $request['body'];
            $post->tags()->detach();
            
            $existingTags = Tag::all()->whereIn('name', $request['tags']);
            
            foreach($request['tags'] as $tag){
                if($oldTag = $existingTags->firstWhere('name', $tag)){
                    $post->tags()->attach($oldTag);
                } else {
                    $newTag = new Tag;
                    $newTag->name = $tag;
                    if($newTag->save()){
                        $post->tags()->attach($newTag);
                    }
                }
            }

            if($request->hasFile('post_image')){
                $filenameToStore = $this->uploadFile($request);
                // Deleting previous image
                if($post->post_image !== 'no_image.jpg')
                    Storage::delete('public/post_images/'.$post->post_image);
                // Updating filename in DB
                $post->post_image = $filenameToStore;
            }

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
            if($post->user_id !== auth()->user()->id)
                return redirect('/posts')->with('error', 'Unauthorized page!');

            if($post->post_image !== 'no_image.jpg')
                Storage::delete('public/post_images/'.$post->post_image);

            $post->delete();
            
            return redirect('/posts')->with('success', 'Post Deleted');
        } else {
            return redirect('/posts')->with('error', 'Post not found.');
        }
    }

    private function uploadFile($request){
        // Gettig the full file name
        $filenameWithExt = $request->file('post_image')->getClientOriginalName();
        // Getting the file name wothout extension
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Getting only the extension
        $fileExt = $request->file('post_image')->getClientOriginalExtension();
        // Checking if the extension equals defauit image extensions
        if($fileExt !== 'jpg' && $fileExt !== 'jpeg' && $fileExt !== 'gif' && $fileExt !== 'bmp' && $fileExt !== 'png' && $fileExt !== 'svg')
            return redirect('/posts/create')->with('error', 'Invalid file extension');
        // Making unique filename
        $filenameToStore = $filename . '_' . time() . '.' . $fileExt;
        // Storing the file
        $path = $request->file('post_image')->storeAs(
            'public/post_images', $filenameToStore
        );
        return $filenameToStore;
    }
}
