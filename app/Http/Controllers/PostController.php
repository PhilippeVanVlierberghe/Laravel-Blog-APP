<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Gate;


class PostController extends BaseController
{
    public function getIndex()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(3);
        return view('blog.index', ['posts' => $posts]);
    }

    public function getAdminIndex()
    {
        //No unauthenticated user is able to make/update posts
        //Zie route file voor middleware oplossing
        /*if (!Auth::check()) {
            return redirect()->back();
        }*/
        $posts = Post::orderBy('title', 'asc')->get();
        return view('admin.index', ['posts' => $posts]);
    }
    public function getPost($id)
    {
        //$post = Post::where('id','=',$id)->first();
        //$post = Post::find($id);
        //niet de with default gebruiken dit is toevallig handig voor dit project = een extra join
        //Dit is een voorbeeld van "eager loading".
        $post = Post::where('id', '=', $id)->with('likes')->first();
        return view('blog.post', ['post' => $post]);
    }

    public function getLikePost($id)
    {
        $post = Post::where('id', '=', $id)->first();
        $like = new Like();
        $post->likes()->save($like);
        return redirect()->back();
    }

    public function getAdminCreate()
    {
        //No unauthenticated user is able to make/update posts
        //Zie route file voor middleware oplossing
        /*if (!Auth::check()) {
            return redirect()->back();
        }*/
        $tags = Tag::all();
        return view('admin.create', ['tags' => $tags]);
    }

    public function getAdminEdit($id)
    {
        //No unauthenticated user is able to make/update posts
        //Zie route file voor middleware oplossing
        /*if (!Auth::check()) {
            return redirect()->back();
        }*/
        $post = Post::find($id);
        $tags = Tag::all();
        return view('admin.edit', ['post' => $post, 'postId' => $id], ['tags' => $tags]);
    }

    public function postAdminCreate(
        Request $request
    ) {

        $request->validate([
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);
        $user = User::find(Auth::id()); // dit is nieuwe code, geen idee of dit zal werken.
        if (!$user) {
            return redirect()->back(); //error message 
        }
        $post = new Post([
            'title' => $request->input('title'),
            'content' => $request->input('content')
        ]);
        $user->posts()->save($post);
        $post->tags()->attach($request->input('tags') === null ? [] : $request->input('tags'));
        return redirect()->route('admin.index')->with('info', 'Post created, Title is: ' . $request->input('title'));
    }

    public function postAdminUpdate(Request $request)
    {
        //No unauthenticated user is able to make/update posts
        //Zie route file voor middleware oplossing
        /*if (!Auth::check()) {
            return redirect()->back();
        }*/
        $request->validate([
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);
        $post = Post::find($request->input('id'));
        //app\Providers\AuthServiceProvider.php
        if(Gate::denies('manipulate-post',$post)){
            return redirect()->back();
        }
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->save();
        //$post->tags()->attach($request->input('tags') === null ? [] : $request->input('tags'));
        $post->tags()->sync($request->input('tags') === null ? [] : $request->input('tags'));
        return redirect()->route('admin.index')->with('info', 'Post edited, new Title is: ' . $request->input('title'));
    }

    //Waarom geen $request data nodig?
    //Dit werk perfect trouwens
    public function getAdminDelete($id)
    {
        //No unauthenticated user is able to make/update posts
        //Zie route file voor middleware oplossing
        /*if (!Auth::check()) {
            return redirect()->back();
        }*/
        $post = Post::find($id);
        if(Gate::denies('manipulate-post',$post)){
            return redirect()->back();
        }
        $post->likes()->delete();/* 1 op n*/
        $post->tags()->detach(); /* n op n*/
        $post->delete();
        return redirect()->route('admin.index')->with('info', 'Post deleted!');
    }
}
