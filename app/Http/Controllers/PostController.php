<?php

namespace App\Http\Controllers;

use App\Post;
use App\Like;
use App\Tag;
use Auth;
use Gate;
use Illuminate\Http\Request;
use DB;

class PostController extends Controller
{
    public function getIndex()
    {
        /* This method i have learn from Pluralsight
          $posts = POST::all();
        */
//        $posts = new Post();
//        $posts = DB::select('select *from posts');
        $posts = POST::orderBy('title', 'desc')->paginate(2);
        return view('blog.index', ['posts' => $posts]);
    }

    public function getAdminIndex()
    {
        /*
//        $post = new Post();
//        $posts = $post->getPosts($session);
//        $posts = new Post();
        */
//        $posts  = DB::select('select *from posts');
        /*
            I have learn this method of fetching data from database from pluralsight
            $posts = POST::all();
        */
        $posts = POST::orderBy('title', 'asc')->get();
        return view('admin.index', ['posts' => $posts]);
    }

    public function getPost($id)
    {
//        $post = new Post();
//        $post = $post->getPost($session, $id);
//        $post = POST::find($id);
//        $post = Post::where('id', $id)->first();
        $post = Post::where('id', '=' , $id)->first();
        return view('blog.post', ['post' => $post]);
    }

    public function getLikePost($id)
    {
//        $post = new Post();
//        $post = $post->getPost($session, $id);
//        $post = POST::find($id);
//        $post = Post::where('id', $id)->first();
        $post = Post::where('id', '=' , $id)->first();
        $like = new Like();
        $post->likes()->save($like);
        return redirect()->back();
    }

    public function getAdminCreate()
    {
        $tags = Tag::all();
        return view('admin.create', ['tags' => $tags]);
    }

    public function getAdminEdit($id)
    {
//        $post = new Post();
//        $post = $post->getPost($session, $id);
        $post = Post::find($id);
        $tags = Tag::all();
//        $post = DB::select('select *from posts where id = ?', [$id]);

        return view('admin.edit', ['post' => $post, 'postId' => $id, 'tags' => $tags]);
    }

    public function postAdminCreate(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);
         // I have learn this method form pluralsight and it offers directly insertion of data without
         //    writing a single line of query
        $user = Auth::user();
        $post = new Post([
            'title' => $request->input('title'),
            'content' => $request->input('content')
        ]);
        $user->posts()->save($post);
        $post->tags()->attach($request->input('tags') === null ? [] : $request->input('tags'));
        // DB::insert('insert into posts ( title, content) values ( ?, ?)', [ $request->input('title'), $request->input('content')]);
        return redirect()->route('admin.index')->with('info', 'Post created, Title is: ' . $request->input('title'));
    }

    public function postAdminUpdate(Request $request)
    {

        $this->validate($request, [
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);
        $post = Post::find($request->input('id'));
        if (Gate::denies('manipulate-post', $post)) {
            return redirect()->back();
        }
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->save();
//        $post = new Post();
//        $post->detach();
//        $post->tags()->attach($request->input('tags') === null ? [] : $request->input('tags'));

        $post->tags()->sync($request->input('tags') === null ? [] : $request->input('tags'));
        return redirect()->route('admin.index')->with('info', 'Post edited, new Title is: ' . $request->input('title'));
    }
    public function getAdminDelete($id)
    {
        $post = Post::find($id);
        if (Gate::denies('manipulate-post', $post)) {
            return redirect()->back();
        }
        $post->likes()->delete();
        $post->tags()->detach();
        $post->delete();
        return redirect()->route('admin.index')->with('info', 'Post deleted!');
    }
}

