<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
        *Check if the user has permission to this method
        */

        if ( Gate::forUser(Auth::user())->allows('post.view') ) {

            //Trying to get all the psot from database
            try {
                // Get all post and create pagination
                $posts = Post::latest()->simplePaginate(6);

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //Return view posts view with the post we got from the database
            return view('post.posts',['posts' => $posts]);

        }

        /*
        * If the user doesn't have permission redirect back
        */
        return redirect()->route('home');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /*
        *Check if the user has permission to this method
        */

        if ( Gate::forUser(Auth::user())->allows('post.view') ) {

            /*
            * Trying to find the post by id with the comments
            */
            try {
                //Trying to get the post data with the comments
                $post = Post::with('user.comments')->findOrFail($id);

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //If we found the post return post view with the $post we found
            //On frontend we check if we found the post
            return view('post.post',['post' => $post]);
        }

        /*
       * If the user doesn't have permission redirect back
       */
        return redirect()->route('home');
    }
}
