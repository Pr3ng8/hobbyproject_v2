<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class CommentsController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CommentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request)
    {
        /*
        *Check if the user has permission to this method
        */

        if ( Gate::forUser(Auth::user())->allows('comment.create') ) {

            //get the validated data from request
            $comment = $request->validated();

            //Add the user id to the array
            $comment['user_id'] = Auth::id();

            //Creating new comment for the post
            $newcomment = new Comment($comment);

            //Insert new comment into database
            try {

                $newcomment->save();

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            // if it was succes redirect back the user to the post
            Session::flash('message', 'Thanks for sharing your thoughts!');
            Session::flash('class', 'alert-success');

            // redirect back the user to the post
            return redirect()->back();

        } else {

            /*
            * If the user doesn't have permission redirect to homepage
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

        if ( Gate::forUser(Auth::user())->allows('comment.view') ) {

        } else {

            /*
            * If the user doesn't have permission redirect to homepage
            */

            return redirect()->route('home');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CommentRequest;  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CommentRequest $request, $id)
    {
        /*
        *Check if the user has permission to this method
        */

        if ( Gate::forUser(Auth::user())->allows('comment.view') ) {

            //Let's try to find the comment by id
            try {

                $comment = Comment::findOrFail($id);

            } catch( \Exception $e ) {

                return $e->getMessage();

            }

            /*
            *Check if this comment belongs to the user and the user can update it
            */
            if ( Gate::forUser(Auth::user())->allows('comment.update', $comment) ) {

                $newcomment = $request->validated();

                try {

                    $comment->update($newcomment);

                } catch ( \Exception $e ) {

                    return $e->getMessage();

                }

                return response()->json([
                    'result' => true,
                    'message' => 'Update was success!',
                    'comment' => $newcomment,
                ]);
            } else {

                /*
                * If the user doesn't have permission redirect to homepage
                */

                return response()->json(['result' => false]);
            }

        } else {

            /*
            * If the user doesn't have permission redirect to homepage
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

        if ( Gate::forUser(Auth::user())->allows('comment.view') ) {

            //check if the id is numeric
            if ( !is_numeric($id) ) {
                // If the id not numeric we send back the usert to the post
                Session::flash('message', 'Sorry something went wrong!');
                Session::flash('class', 'alert-danger');

                // redirect back the user to the post
                return redirect()->back();
            }

            //Insert new comment into database
            try {

                //Trying to find the comment by id
                $comment = Comment::findOrFail($id);

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //Check if that the comments belogs to the current authenticated user
            if ( Gate::forUser(Auth::user())->allows('comment.delete', $comment) ) {

                //Check if we found the comment
                if ( !$comment->id ) {

                    // if we didn't found the comment send back warning message
                    Session::flash('message', 'Sorry we could not delete the comment!');
                    Session::flash('class', 'alert-warning');

                    // redirect back the user to the post
                    return redirect()->back();

                }

                //If we found the comment let's try to delete it
                try {

                    //Trying to delete comment
                    $comment->delete();

                } catch(\Exception $e) {

                    return $e->getMessage();
                }

                //Check if we successfully deleted the comment
                if ( $comment->trashed() ) {

                    // if it was succes redirect back the user to the post
                    Session::flash('message', 'We have deleted your comment successfully!');
                    Session::flash('class', 'alert-success');

                    // redirect back the user to the post
                    return redirect()->back();
                }

                // if it was succes redirect back the user to the post
                Session::flash('message', 'Sorry we could not delete the comment!');
                Session::flash('class', 'alert-warning');

                // redirect back the user to the post
                return redirect()->back();

            } else {

                // If the comment doesn't belongs to this user riderct the user back
                Session::flash('message', 'Sorry we could not delete the comment!');
                Session::flash('class', 'alert-warning');

                // redirect back the user to the post
                return redirect()->back();

            }

        } else {

            /*
            * If the user doesn't have permission redirect to homepage
            */

            return redirect()->route('home');
        }
    }
}
