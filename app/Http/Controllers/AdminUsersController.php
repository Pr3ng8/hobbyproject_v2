<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\AdminSearchUserRequest;
use App\Http\Requests\AdminUserRequest;
use App\Models\Role;
use App\Search\UserSearch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  $request
     * @return \Illuminate\Http\Response
     */
    public function index(AdminSearchUserRequest $request)
    {
        /*
        *Check if the user has permission to this method
        */

        if ( Gate::forUser(Auth::user())->allows('admin-user.view') ) {

            //Check the $request if it caontains any filter
            if( $request->get('name') === "all" && $request->get('status') === "all" && $request->get('usersstatus') === "all" || count($request->all()) === 0 ) {

                try {

                    //If its not we just simply list out all the users and paginate it
                    $users = User::
                    //Get soft deleted users too
                    withTrashed()
                        //Order them by created_at
                        ->latest()
                        //We dont want the currently authenticated user
                        ->where('id','!=',Auth::id());

                } catch ( \Exception $e ) {

                    return $e->getMessage();

                }


            } else {
                //Create a search instance so we can give the parametrs to it if there is any

                try {

                    $users = (new UserSearch)::apply($request);

                } catch ( \Exception $e) {

                    return $e->getMessage();

                }

            }


            try {

                //Creating pagination from the result
                $users = $users->paginate(10);

            } catch ( \Exception $e) {

                return $e->getMessage();
            }

            try {

                //We want all role except administrator
                $roles = Role::all();

            } catch ( \Exception $e) {

                return $e->getMessage();

            }

            //return users view where we list out all the results
            return view('admin.users.users',['users' => $users, 'roles' => $roles]);

        } else {

            /*
            * If the user doesn't have permission redirect to homepage
            */

            return redirect()->route('home');
        }
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

        if ( Gate::forUser(Auth::user())->allows('admin-user.view') ) {

            //Let's try to find the user but we do not want the currenttly authenticated user

            //Check if the id is numeric
            if ( !is_numeric($id) ) {

                //Create warning message about we could not find the user
                Session::flash('message', 'We are sorry, we could not found the user!!');
                Session::flash('class', 'alert-warning');

                //Redirect back the user
                return redirect()->back();
            }

            try {
                //Find the user by id in the database
                $user = User::withTrashed()->where('id','!=',Auth::id())->findOrFail($id);

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //Get all the comments thats belongs to the user
            try {

                $comments = Comment::withTrashed()->where('user_id', $user->id)->latest()->get();

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //return view where whe show the user's data
            return view('admin.users.user',['user' => $user,'comments' => $comments]);

        } else {

            /* If the user doesn't have
            * permission we redirect him to the home page
            */

            return redirect('home');

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

        if ( Gate::forUser(Auth::user())->allows('admin-user.view') ) {

            //Check if the id is numeric
            if ( !is_numeric($id) ) {

                //Create warning message about we could not find the user
                Session::flash('message', 'We are sorry, we could not found the user!');
                Session::flash('class', 'alert-warning');

                //Redirect back the user
                return redirect()->back();
            }

            //Let's try to find the user but not the currenttly authenticated one
            try {
                //Find user with soft deleted, by id
                $user = User::
                where('id','!=',Auth::id())
                    ->withTrashed()
                    ->findOrFail($id);

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //get all the role from table to list them out if we want to edit
            try {

                $roles = Role::all();

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //return edit view with roles and the user
            return view('admin.users.edit',['user' => $user,'roles' => $roles]);

        } else {

            /* If the user doesn't have
            * permission we redirect him to the home page
            */

            return redirect('home');

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AdminUserRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminUserRequest $request, $id)
    {
        /*
        *Check if the user has permission to this method
        */

        if ( Gate::forUser(Auth::user())->allows('admin-user.update') ) {

            //Check if the id is numeric
            if ( !is_numeric($id) ) {

                //Create warning message about we could not find the user
                Session::flash('message', 'We are sorry, we could not found the user!');
                Session::flash('class', 'alert-warning');

                //Redirect back the user
                return redirect()->back();
            }

            try {
                //Trying to find the user by id
                $user = User::findOrFail($id);

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            // Checking if we found the user
            if ( !$user ) {

                // If we didn't we redirect back the user with warning message
                Session::flash('message', 'We are sorry we could not found the user!!');
                Session::flash('class', 'alert-warning');

                //Redirect back the user
                return redirect()->back();

            }

            /*
            *Recieve the validated data  from $request
            */
            $data = $request->validated();

            try {
                // Updating the user role in the pivot table
                $user->roles()->sync([1 => ['role_id' => $data['role_id']]]);

            } catch( \Exception $e ) {

                return $e->getMessage();
            }

            try {

                //Updateing the user status in the users_status table
                $user->status->update($data);

            } catch ( \Exception $e ) {

                return $e->getMessage();

            }

            // Message to the usser if the update was succesfull
            Session::flash('message', 'We succesfully updated the user\' data!');
            Session::flash('class', 'alert-success');

            //Redirect back the user
            return redirect()->route('admin.users.index');

        } else {

            /* If the user doesn't have
            * permission we redirect him to the home page
            */

            return redirect('home');

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
        if ( Gate::forUser(Auth::user())->allows('admin-user.delete') ) {

            //Check if the id is numeric
            if ( !is_numeric($id) ) {

                //Create warning message about we could not find the user
                Session::flash('message', 'We are sorry, we could not found the user!');
                Session::flash('class', 'alert-warning');

                //Redirect back the user
                return redirect()->back();
            }

            try {
                //Fidn the user but not the currenttly authenticated user by id
                $user = User::
                where('id','!=',Auth::id())
                    ->findOrFail($id);

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            try {
                //Soft deelete the user from database
                $user->delete();

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //Check if we successfully deleted the user
            if( $user->trashed() ) {

                //If we deleted the user successfully create success message
                Session::flash('message', 'We successfully deleted the user!');
                Session::flash('class', 'alert-success');

                //Return with list of users
                return redirect()->route('admin.users.index');
            }

            //If we could not delete the user return error message
            Session::flash('message', 'Sorry ,We couldn\'t delete the user!');
            Session::flash('class', 'alert-danger');

            //Return with list of users
            return redirect()->route('admin.users.index');

        }  else {

            /* If the user doesn't have
            * permission we redirect him to the home page
            */

            return redirect('home');

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
        if ( Gate::forUser(Auth::user())->allows('admin-user.view') ) {

            //Check if the id is numeric
            if ( !is_numeric($id) ) {

                //Create warning message about we could not find the user
                Session::flash('message', 'We are sorry, we could not found the user!');
                Session::flash('class', 'alert-warning');

                //Redirect back the user
                return redirect()->back();
            }

            try {
                //Trying to find the user among the soft deleted users
                $user = User::onlyTrashed()->findOrFail($id);

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //Check if we found the user
            if ( !$user->id ) {
                //Create message that we could not find the user
                Session::flash('message', 'We are sorry, we could not found the user!');
                Session::flash('class', 'alert-warning');

                //Redirect back the user
                return redirect()->back();
            }

            //If we found the user let's try to restore it
            try {
                //Restore the user in database
                $user->restore();

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //Check if we could not delte the iser
            if( $user->trashed() ) {

                //Create warning message about we could not restore it
                Session::flash('message', 'We couldn\'t restore the user!');
                Session::flash('class', 'alert-danger');

                //Redirext the user to list of users
                return redirect('admin.users.index');
            }

            //If we found the user create success message
            Session::flash('message', 'We successfully restored the user!');
            Session::flash('class', 'alert-success');

            //redirect the user to the list of users
            return redirect()->route('admin.users.index');

        } else {

            /* If the user doesn't have
            * permission we redirect him to the home page
            */

            return redirect('home');

        }
    }

    /**
     *  Show the form for searching the specified resource.
     *
     * @param  \App\Http\Requests\AdminSearchUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function search(AdminSearchUserRequest $request)
    {
        /*
        *Check if the user has permission to this method
        *
        */

        if ( Gate::forUser(Auth::user())->allows('admin-user.view') ) {

            //Let's try to get all the role except that has id 1
            try {

                $roles = Role::all();

            } catch ( \Exception $e) {

                return $e->getMessage();

            }
            //Check if any of the filter option is filled
            if ( $request->filled('first_name')
                || $request->filled('last_name')
                || $request->filled('email')
                || $request->filled('birthdate')
                || $request->filled('roles')
                || $request->filled('status')
                || $request->get('userstatus') !== 'all'
            ) {

                try {

                    $users = (new UserSearch)::apply($request);

                } catch ( \Exception $e) {

                    return $e->getMessage();

                }

                //Creating pagination from the result
                $users = $users->paginate(10);
            } else {
                //If no filter was filled we simply set the $users to null
                $users = NULL;
            }

            return view('admin.users.search',['users' => $users,'roles' => $roles]);

        } else {

            /* If the user doesn't have
            * permission we redirect him to the home page
            */

            return redirect('home');

        }
    }
}
