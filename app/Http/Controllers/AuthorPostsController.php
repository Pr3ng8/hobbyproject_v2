<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\UploadFile\UploadPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class AuthorPostsController extends Controller
{

    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*
        *Check if the user has permission to this method
        */

        if ( Gate::forUser(Auth::user())->allows('post.view') ) {

            //Get from $request the poststatus var to determinate how we want to filter the posts when listing out them
            $listmode = $request->get('postsstatus') ?? "all";

            //Using switch tho determiante which posts the user want to list out
            switch ($listmode) {

                //If the user wnat to list out deleted and active post we enter this case
                case "all":

                    //Trying to get all the post both soft deleted anc active
                    try {

                        $posts = Post::withTrashed()->latest()->where('user_id',Auth::id())->paginate(15);

                    } catch ( \Exception $e ) {

                        return $e->getMessage();
                    }

                    break;

                //If the user only wnats the active post we enter in this case
                case "active":

                    //Trying to get all the active post and paginate it
                    try {

                        $posts = Post::where('user_id',Auth::id())->latest()->paginate(15);

                    } catch ( \Exception $e ) {

                        return $e->getMessage();

                    }

                    break;

                //If the user only wants the deleted posts we enter this case
                case "trashed":

                    //Tring to get all the soft deleted post and paginate it
                    try {

                        $posts = Post::onlyTrashed()->where('user_id',Auth::id())->latest()->paginate(15);

                    } catch ( \Exception $e ) {

                        return $e->getMessage();

                    }

                    break;

                //If we enter none of the cases we use the default and show deleted and active posts
                default:

                    //Trying to get all the post both soft deleted anc active
                    try {

                        $posts = Post::withTrashed()->where('user_id',Auth::id())->latest()->paginate(15);

                    } catch ( \Exception $e ) {

                        return $e->getMessage();

                    }

            }

            //After we got all the post we return posts view and the posts to the user
            return view('author.posts.posts',['posts' => $posts]);

        } else {

            /*
            * If the user doesn't have permission redirect to home page
            */
            return redirect()->route('home');

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*
        *Check if the user has permission to this method
        */
        if ( Gate::forUser(Auth::user())->allows('post.create') ) {
            //Return post create view
            return view('author.posts.create');

        } else {
            /*
            * If the user doesn't have permission redirect to home page
            */
            return redirect()->route('home');

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $asd = "";
        $data = $request->;
        dd($data);
        return response()->json($data);
        /*
        *Check if the user has permission to this method
        */
        if ( Gate::forUser(Auth::user())->allows('post.create') ) {

            //Get all the validated data from request
            $data = $request->validated();

            //We take out the photo data from $data when we creating a new post
            unset($data['file']);

            //Adding to the $data the authenticated user id
            $data['user_id'] = Auth::id();

            //creating new post instance
            $post = new Post($data);

            //Trying to insert the new post in database
            try{
                //Inserting the post in database
                $post->save();

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //Checking if the user wants to upload photo to the post
            if ( $request->hasFile('file') && $request->file('file')->isValid() ) {

                try {

                    $check = (new UploadPhoto)->upload($request, $post);

                } catch ( \Exception $e) {

                    return $e->getMessage();

                }

            }


            //Check if the post was succesfully inserted in the database
            if ( !$post->id && !$check && Session::has('type') && Session::has('size') && Session::has('extension')) {
                //If the post was not inserted create error message
                Session::flash('message', 'We could not save the new post,sorry!!');
                Session::flash('class', 'alert-danger');
                //Return redirect to post create with error message
                return redirect()->route('author.posts.create');
            }

            //Create success message to the user telling the user we inserted created the post successfully
            Session::flash('message', 'New post was created successfully!');
            Session::flash('class', 'alert-info');

            //Redirect the user to the post
            return redirect()->route('posts');

        } else {

            /*
            * If the user doesn't have permission redirect to home page
            */
            return redirect()->route('home');
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
        /*
        *Check if the user has permission to this method
        */

        if ( Gate::forUser(Auth::user())->allows('post.view') ) {

            //Check if the $id is numeric
            if ( !is_numeric($id) ) {

                Session::flash('message', 'We are sorry something went worng!');
                Session::flash('class', 'alert-warning');

                //Redirect back the user with warning message
                return redirect()->back();
            }

            //Trying to find the post by $id
            try{
                //Get the post by $id even if its soft deleted
                $post = Post::withTrashed()->findOrFail($id);

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //Check if the post belongs to the user
            if ( Gate::forUser(Auth::user())->allows('post.edit', $post) ) {
                //If the post belongs to the user let it edit

                //Return edit view with the post
                return view('author.posts.edit', ['post' => $post]);

            } else {
                //Unset the $post variable
                unset($post);

                //If the post doesn't belongs to the user then redirect back with warning message
                Session::flash('message', 'Sorry you can not edit this post!');
                Session::flash('class', 'alert-warning');

                //Redirect back the user with warning message
                return redirect()->route('author.posts.index');
            }


        } else {
            /*
            * If the user doesn't have permission redirect to home page
            */
            return redirect()->route('home');

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PostRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, $id)
    {
        /*
        *Check if the user has permission to this method
        */

        if ( Gate::forUser(Auth::user())->allows('post.view') ) {
            //Check if the $id is numeric
            if ( !is_numeric($id) ) {

                Session::flash('message', 'We are sorry something went worng!');
                Session::flash('class', 'alert-warning');

                //Redirect back the user with warning message
                return redirect()->back();
            }

            //Let's try to get the post by id
            try{
                //Want to find the post by id even if its deleted
                $post = Post::withTrashed()->findOrFail($id);

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //Check if the post belongs to the user
            if ( Gate::forUser(Auth::user())->allows('post.update', $post) ) {
                //If the post belongs to the user let it update

                //Get the validated data from $request
                $data = $request->validated();

                //Check if we found the post
                if ( !$post->id ) {
                    //Create warning message about we could not find the post
                    Session::flash('message', 'Sorry we could not find the post!');
                    Session::flash('class', 'alert-warning');

                    //Redirect back the user with warning message
                    return redirect()->back();
                }

                //Checking if the user wants to upload photo to the post
                if ( $request->hasFile('file') && $request->file('file')->isValid() ) {

                    try {

                        $check = (new UploadPhoto)->upload($request, $post);

                    } catch ( \Exception $e) {

                        return $e->getMessage();

                    }

                }

                //if we found it let's try to update it
                try{
                    //Update the post with the data we recieved from $request
                    $post->update($data);

                } catch(\Exception $e) {

                    return $e->getMessage();
                }

                //If we succesfull updated the post send success message to user
                Session::flash('message', 'Post was updated successfully!');
                Session::flash('class', 'alert-info');

                //Redirect the user back to the list of posts
                return view('post.post',['post' => $post]);

            } else {
                //Unset the $post variable
                unset($post);

                //If the post doesn't belongs to the user then redirect back with warning message
                Session::flash('message', 'Sorry you can not update this post!');
                Session::flash('class', 'alert-warning');

                //Redirect back the user with warning message
                return redirect()->route('author.posts.index');;

            }

        } else {
            /*
            * If the user doesn't have permission redirect to home page
            */
            return redirect()->route('home');

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
        /*
        *Check if the user has permission to this method
        */

        if ( Gate::forUser(Auth::user())->allows('post.view') ) {

            //Checking if the $id is numeric
            if ( !is_numeric($id) ) {
                //If it's not numeric redirect back the user with warning message
                Session::flash('message', 'Sorry something went wrong!');
                Session::flash('class', 'alert-warning');
                //Redirect back the user
                return redirect()->back();
            }

            //Let's try to find the post by id
            try{
                //Find post by $id in database
                $post = Post::withTrashed()->findOrFail($id);

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //Check if the post belongs to the user
            if ( Gate::forUser(Auth::user())->allows('post.delete', $post) ) {
                //If the post belongs to the user let it delete

                //Check if the post already deleted
                if ( $post->trashed() ) {

                    //if its already deleted then create warning message
                    Session::flash('message', 'The Post already deleted!');
                    Session::flash('class', 'alert-warning');

                    //redirect back the user
                    return redirect()->back();
                }

                //Let's try to delete the post
                try{

                    //Soft deleting the post from database
                    $post->delete();


                } catch(\Exception $e) {

                    return $e->getMessage();
                }


                //Check if we successfully soft deleted the post
                if ( $post->trashed() ) {

                    //If we successfully sift deleted the post create success message
                    Session::flash('message', 'Post Deleted Successfully!');
                    Session::flash('class', 'alert-info');

                    //Redirect back the user
                    return redirect()->back();

                }

                //If we could not delete the post create error message
                Session::flash('message', 'We are sorry, we couldn\'t delete the post!');
                Session::flash('class', 'alert-danger');

                //redirect back the user
                return redirect()->back();

            } else {
                //Unset the $post variable
                unset($post);

                //If the post doesn't belongs to the user then redirect back with warning message
                Session::flash('message', 'Sorry you can not delete this post!');
                Session::flash('class', 'alert-warning');

                //Redirect back the user with warning message
                return redirect()->route('author.posts.index');;
            }

        } else {
            /*
            * If the user doesn't have permission redirect to home page
            */
            return redirect()->route('home');

        }

    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        /*
        *Check if the user has permission to this method
        */

        if ( Gate::forUser(Auth::user())->allows('post.view') ) {

            //Checking if the $id is numeric
            if ( !is_numeric($id) ) {

                //If it's not numeric redirect back the user with warning message
                Session::flash('message', 'Sorry something went wrong!');
                Session::flash('class', 'alert-warning');

                //Redirect back the user
                return redirect()->back();
            }
            //Let's try to find the post by id even if its deleted
            try {

                $post = Post::onlyTrashed()->findOrFail($id);

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //Check if the post belongs to the user
            if ( Gate::forUser(Auth::user())->allows('post.restore', $post) ) {
                //If the post belongs to the user let it update

                //Check if the post deleted
                if ( !$post->trashed() ) {

                    //If the post was not deleted send back warning message
                    Session::flash('message', 'The post was not deleted!');
                    Session::flash('class', 'alert-warning');

                    //Redirect back the user
                    return redirect()->back();

                }

                //Lets try to restore the post
                try {
                    //Restore the post in database
                    $post->restore();

                } catch(\Exception $e) {

                    return $e->getMessage();
                }


                //If we successfully restrored the post create succes message
                Session::flash('message', 'We have successfully resotred the post!');
                Session::flash('class', 'alert-success');

                //Redirect back the user
                return redirect()->back();

            } else {
                //Unset the $post variable
                unset($post);

                //If the post doesn't belongs to the user then redirect back with warning message
                Session::flash('message', 'Sorry you can not restore this post!');
                Session::flash('class', 'alert-warning');

                //Redirect back the user with warning message
                return redirect()->route('author.posts.index');;
            }


        } else {
            /*
            * If the user doesn't have permission redirect to home page
            */
            return redirect()->route('home');

        }

    }
}
