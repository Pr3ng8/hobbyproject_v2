<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class AdminPostsController extends Controller
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

        if ( Gate::forUser(Auth::user())->allows('admin-post.viewAny') ) {

            //Get from $request the poststatus var to determinate how we want to filter the posts when listing out them
            $listmode = $request->get('postsstatus') ?? "all";

            //Using switch tho determiante which posts the user want to list out
            switch ($listmode) {

                //If the user wnat to list out deleted and active post we enter this case
                case "all":

                    //Trying to get all the post both soft deleted anc active
                    try {

                        $posts = Post::withTrashed()->latest()->paginate(15);

                    } catch ( \Exception $e ) {

                        return $e->getMessage();
                    }

                    break;

                //If the user only wnats the active post we enter in this case
                case "active":

                    //Trying to get all the active post and paginate it
                    try {

                        $posts = Post::latest()->paginate(15);

                    } catch ( \Exception $e ) {

                        return $e->getMessage();

                    }

                    break;

                //If the user only wants the deleted posts we enter this case
                case "trashed":

                    //Tring to get all the soft deleted post and paginate it
                    try {

                        $posts = Post::onlyTrashed()->latest()->paginate(15);

                    } catch ( \Exception $e ) {

                        return $e->getMessage();

                    }

                    break;

                //If we enter none of the cases we use the default and show deleted and active posts
                default:

                    //Trying to get all the post both soft deleted anc active
                    try {

                        $posts = Post::withTrashed()->latest()->paginate(15);

                    } catch ( \Exception $e ) {

                        return $e->getMessage();

                    }

            }

            //After we got all the post we return posts view and the posts to the user
            return view('admin.posts.posts',['posts' => $posts]);

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

        if ( Gate::forUser(Auth::user())->allows('admin-post.edit') ) {

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

            //Return edit view with the post
            return view('admin.posts.edit', ['post' => $post]);

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

        if ( Gate::forUser(Auth::user())->allows('admin-post.update') ) {

            //Check if the $id is numeric
            if ( !is_numeric($id) ) {

                Session::flash('message', 'We are sorry something went worng!');
                Session::flash('class', 'alert-warning');

                //Redirect back the user with warning message
                return redirect()->back();
            }

            //Get the validated data from $request
            $data = $request->validated();

            //Let's try to get the post by id
            try{
                //Want to find the post by id even if its deleted
                $post = Post::withTrashed()->findOrFail($id);

            } catch(\Exception $e) {

                return $e->getMessage();
            }

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

        if ( Gate::forUser(Auth::user())->allows('admin-post.delete') ) {

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
                //Find post by id in database
                $post = Post::findOrFail($id);

            } catch(\Exception $e) {

                return $e->getMessage();
            }

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

        if ( Gate::forUser(Auth::user())->allows('admin-post.restore') ) {

            //Checking if the $id is numeric
            if ( !is_numeric($id) ) {

                //If it's not numeric redirect back the user with warning message
                Session::flash('message', 'Sorry something went wrong!');
                Session::flash('class', 'alert-warning');

                //Redirect back the user
                return redirect()->back();
            }

            try {

                $post = Post::onlyTrashed()->findOrFail($id);

            } catch(\Exception $e) {

                return $e->getMessage();
            }

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


            //Check if the post deleted
            if ( $post->trashed() ) {

                //If we could not restore the post send back error message
                Session::flash('message', 'The post was not deleted!');
                Session::flash('class', 'alert-danger');

                //Redirect back the user
                return redirect()->back();

            }

            //If we successfully restrored the post create succes message
            Session::flash('message', 'We have successfully resotred the post!');
            Session::flash('class', 'alert-success');

            //Redirect back the user
            return redirect()->back();

        } else {
            /*
            * If the user doesn't have permission redirect to home page
            */
            return redirect()->route('home');

        }

    }
}
