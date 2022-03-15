<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\UserRequest;
use App\UploadFile\UploadPhoto;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
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

        //Trying to get the user by id which saved in the session
        try {

            $user = User::findOrFail(Auth::id());

        } catch ( \Exception $e) {

            return $e->getMessage();

        }

        //Check if it is the same user
        if ( Gate::forUser(Auth::user())->allows('user.view', $user->id) ) {

            //If it is the same user then lets get the comments with it
            //Get the comments that are belongs to the user and paginate it
            try {

                $comments = Comment::where('user_id', Auth::id())->latest()->paginate(10);

            } catch ( \Exception $e) {

                return $e->getMessage();

            }

            //Return profile view and user and comments data
            return view('user.profile',['user' => $user,'comments' => $comments]);

        } else {

            //Unset the $user we found
            unset($user);

            /*
            * If the user doesn't have permission redirect to homepage
            */

            return redirect()->route('login');
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

        //Check if the $id is numeric
        if ( !is_numeric($id) ) {

            Session::flash('message', 'We are sorry something went worng!');
            Session::flash('class', 'alert-warning');

            //Redirect back the user with warning message
            return redirect()->back();
        }

        //Trying to get the user by id which saved in the session
        try {

            $user = User::findOrFail($id);

        } catch ( \Exception $e) {

            return $e->getMessage();

        }

        //Check if it is the same user
        if ( Gate::forUser(Auth::user())->allows('user.update', $user->id) ) {

            //If it is the same user then return the edit profile view
            return view('user.edit',['user' => $user]);

        } else {

            //Unset the $user we found
            unset($user);

            /*
            * If the user doesn't have permission redirect to homepage
            */

            return redirect()->route('login');

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        //Check if the $id is numeric
        if ( !is_numeric($id) ) {

            Session::flash('message', 'We are sorry something went worng!');
            Session::flash('class', 'alert-warning');

            //Redirect back the user with warning message
            return redirect()->back();
        }

        //Trying to get the user by id which saved in the session
        try {

            $user = User::findOrFail($id);

        } catch ( \Exception $e) {

            return $e->getMessage();

        }

        //Check if it is the same user
        if ( Gate::forUser(Auth::user())->allows('user.update', $user->id) ) {

            //Checking if the user wants to upload photo to the profile
            if ( $request->hasFile('file') && $request->file('file')->isValid() ) {

                try {

                    $check = (new UploadPhoto)->upload($request, $user);

                } catch ( \Exception $e) {

                    return $e->getMessage();

                }

            }


            //Get the validated data from request
            $data = $request->validated();

            //Let's try to update the user with tha validated data
            try {

                $user->update($data);

            } catch ( \Exception $e ) {

                return $e->getMessage();

            }
            //Create succcess message that we have updated the user~s data
            Session::flash('message', 'We have updated your data!');
            Session::flash('class', 'alert-success');

            //If it is the same user then return the edit profile view
            return redirect()->route('user.index');

        } else {

            //Unset the $user we found
            unset($user);

            /*
            * If the user doesn't have permission redirect to homepage
            */

            return redirect()->route('login');

        }
    }
}
