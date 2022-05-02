<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\Tag;

class PostController extends BaseController
{
    public function getIndex()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(3);
        return view('blog.index', ['posts' => $posts]);
    }

    public function getAdminIndex()
    {
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
        $tags = Tag::all();
        return view('admin.create',['tags' => $tags]);
    }

    public function getAdminEdit($id)
    {
        $post = Post::find($id);
        $tags = Tag::all();
        return view('admin.edit', ['post' => $post, 'postId' => $id],['tags' => $tags]);
    }

    public function postAdminCreate(
        Request $request
    ) {

        $request->validate([
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);
        /*
        $post = new Post();
        $post->addPost($session, $request->input('title'), $request->input('content'));
        */
        $post = new Post([
            'title' => $request->input('title'),
            'content' => $request->input('content')
        ]);
        $post->save();
        $post->tags()->attach($request->input('tags') === null ? [] : $request->input('tags'));
        return redirect()->route('admin.index')->with('info', 'Post created, Title is: ' . $request->input('title'));
    }

    public function postAdminUpdate(
        Request $request
    ) {
        $request->validate([
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);
        $post = Post::find($request->input('id'));
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
        $post = Post::find($id);
        $post->likes()->delete();/* 1 op n*/
        $post->tags()->detach(); /* n op n*/
        $post->delete();
        return redirect()->route('admin.index')->with('info', 'Post deleted!');
    }
}
