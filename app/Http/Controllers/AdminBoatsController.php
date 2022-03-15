<?php

namespace App\Http\Controllers;

use App\Models\Boat;
use App\Http\Requests\AdminBoatRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class AdminBoatsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*
        * Check if the user has permission to this method
        */

        if ( Gate::forUser(Auth::user())->allows('boat.view') ) {

            //Check if the filteration is filled
            if( $request->get('status') === "all" || count($request->all()) === 0 ) {
                //If we dont wnat to filter just list out we use this

                //Get alll the boats with deleted ones
                $boats = Boat::
                withTrashed()
                    ->paginate(10);;

            } else {

                //if we want to filter it we check what are the conditions
                $boats = new Boat();

                /*
                * Determinating with switch statement if we wnat to get the
                * deleted boats,active boats or both.
                */
                switch ( $request->get('status') ) {

                    //Only active boats
                    case "active" :
                        $boats = $boats->all();
                        break;

                    //Only deleted boats
                    case "trashed" :
                        $boats = $boats->onlyTrashed();
                        break;
                    //Default is both, deleted and active boats
                    default :
                        $boats = $boats->withTrashed();
                        break;
                }
            }

            //Redirect the user to the page with the boats where we list them out
            return view( 'admin.boats.boats', ['boats' => $boats] );

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

        //Check if the user has permission to this method
        if ( Gate::forUser(Auth::user())->allows('boat.create') ) {

            //Redirect the user to the page where he can create boats
            return view('admin.boats.create');

        } else {

            //If the user doesn't have permission redirect to home page
            return redirect()->route('home');

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AdminBoatRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminBoatRequest $request)
    {
        /*
        *Check if the user has permission to this method
        */

        if ( Gate::forUser(Auth::user())->allows('boat.create') ) {

            //Get the validated data from request
            $data = $request->validated();

            //Creating new boat model with the validated data
            $boat = new Boat($data);

            try{
                //Inserting the boat to the database
                $boat->save();

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //Checking if the user wants to upload photo for the boat
            if ( $request->hasFile('file') && $request->file('file')->isValid() ) {

                try {

                    $check = (new UploadPhoto)->upload($request, $boat);

                } catch ( \Exception $e) {

                    return $e->getMessage();

                }

            }

            //Creating Session message to inform the user if it was success
            Session::flash('message', 'New boat was created successfully!');
            //Style of the message
            Session::flash('class', 'alert-info');

            //REdirect the user back where we list out all the boats
            return redirect()->route('admin.boats.index');


        } else {

            /*
            * If the user doesn't have permission redirect to home page
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
        if ( Gate::forUser(Auth::user())->allows('boat.view') ) {

            //Check if the id is numeric
            if (!is_numeric($id)) {

                /* If its not redirect the user to the list of boats and setting
                * the boat variable to null telling user we could not find it
               */
                $boat = NULL;

                return view( 'admin.boats.boat', ['boat' => $boat] );
            }

            try{

                //If the id was numeric then lets try to find the boat by id
                $boat = Boat::withTrashed()->findOrFail($id);

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //Check if we foun the boat
            if ( !$boat ) {

                //Set the boat to null beacuse we didn't find it
                $boat = NULL;

                //If we didn't find the boat make warning message and send back it to user
                Session::flash('message', 'We could not find the post you were looking for!');
                Session::flash('class', 'alert-warning');

                //Show boat profile for user and send back the empty boat
                return view( 'admin.boats.boat', ['boat' => $boat] );
            }

            //If we found the boat show boat profile and send the bats data to view
            return view( 'admin.boats.boat', ['boat' => $boat] );

        } else {
            /*
            * If the user doesn't have permission redirect back
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
        if ( Gate::forUser(Auth::user())->allows('boat.view') ) {

            //Check if the id is numeric
            if (!is_numeric($id)) {

                /* If its not redirect the user to the list of boats and setting
                * the boat variable to null telling user we could not find it
               */
                $boat = NULL;

                return view( 'admin.boats.edit', ['boat' => $boat] );
            }

            try{

                //If the id was numeric then lets try to find the boat by id
                $boat = Boat::withTrashed()->findOrFail($id);

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //Check if we found the boat
            if ( !$boat) {

                //Set the boat to null beacuse we didn't find it
                $boat = NULL;

                //If we didn't find the boat make warning message and send back it to user
                Session::flash('message', 'We could not find the post you were looking for!');
                Session::flash('class', 'alert-warning');

                //Show boat edit for user and send back the empty boat
                return view( 'admin.boats.edit', ['boat' => $boat] );
            }

            //Load edit boat view for the user and send the boat data with it
            return view( 'admin.boats.edit', ['boat' => $boat] );

        } else {
            /*
            * If the user doesn't have permission redirect back
            */

            return redirect()->route('home');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AdminBoatRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminBoatRequest $request, $id)
    {
        /*
        *Check if the user has permission to this method
        */

        if ( Gate::forUser(Auth::user())->allows('boat.update') ) {

            //Check if the id is numeric
            if ( !is_numeric($id) ) {

                //If the id not numeric redirect back the user with error message
                Session::flash('message', 'We could not update the boat\'s data, sorry!');
                Session::flash('class', 'alert-danger');

                //Redirect back the user
                return redirect()->back();
            }
            try{

                //If the id was numeric then lets try to find the boat by id
                $boat = Boat::withTrashed()->findOrFail($id);

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //Check if we found the voat
            if ( !$boat->id ) {

                //If we didn't find the boat we redirect back the user with error message
                Session::flash('message', 'We could not find the boat, sorry!');
                Session::flash('class', 'alert-danger');

                //Redirect back the user
                return redirect()->back();
            }

            //Checking if the user wants to upload photo for the boat
            if ( $request->hasFile('file') && $request->file('file')->isValid() ) {

                try {

                    $check = (new UploadPhoto)->upload($request, $boat);

                } catch ( \Exception $e) {

                    return $e->getMessage();

                }

            }

            // Get the validated data from $request
            $data = $request->validated();

            try {
                // Updating the boat's data what we recieved from request
                $boat->update($data);

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //If we successfully updated the boat data reidrect the user to the list of boats with usccess message
            Session::flash('message', 'We have successfully updated the boat\'s data');
            Session::flash('class', 'alert-success');

            //Redirect the user back to list of boats
            return redirect()->route('admin.boats.index');
        } else {

            /*
            * If the user doesn't have permission redirect back
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

        if ( Gate::forUser(Auth::user())->allows('boat.delete') ) {

            //Check if the id is numeric
            if ( !is_numeric($id) ) {

                //If it's not riderct the user back with warning message
                Session::flash('message', 'We could not find the boat, sorry!');
                Session::flash('class', 'alert-warning');

                //Redirect the user back where he was previously
                return redirect()->back();
            }

            try {
                //Lets try to find the boat by id
                $boat = Boat::findOrFail($id);

            } catch (\Exception $e) {

                return $e->getMessage();

            }

            //Check if we found the boat
            if ( !$boat ) {
                //If we didn't find the boat send back error message
                Session::flash('message', 'We could not find the boat, sorry!');
                Session::flash('class', 'alert-danger');

                //Redirect back the user where he was previously
                return redirect()->back();
            }

            //If we found the boat let's try to delete it
            try {

                //Soft deleting the boat
                $boat->delete();

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //Check if we put the boat in the trash
            if( $boat->trashed() ) {

                //If we trased the boat redirect the user to the boats with success message
                Session::flash('message', 'We successfully deleted the boat!');
                Session::flash('class', 'alert-success');

                // REdirect the user to the list of boats
                return redirect()->route('admin.boats.index');
            }

            //If we couldn't delete the boat send warning message to the user
            Session::flash('message', 'Sorry ,We couldn\'t delete the boat!');
            Session::flash('class', 'alert-warning');

            //Redirect back the user to the list of boats
            return redirect()->route('admin.boats.index');

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

        if ( Gate::forUser(Auth::user())->allows('boat.create') ) {

            //Check if the id is numeric
            if ( !is_numeric($id) ) {

                //If the id is not numeric send back warning message
                Session::flash('message', 'We could not find the boat, sorry!');
                Session::flash('class', 'alert-warning');

                //Redirect back the user where he was previously
                return redirect()->back();
            }

            //Let's try to find the boat among the deleted boats
            try {
                //Trying to find the boat by id in the soft deleted boats
                $boat = Boat::onlyTrashed()->findOrFail($id);

            } catch (\Exception $e) {

                return $e->getMessage();
            }

            //Check if we found the boat
            if ( !$boat ) {
                //If we didn't find the boat send warning message back
                Session::flash('message', 'We could not find the boat, sorry!');
                Session::flash('class', 'alert-danger');

                //Redirect the user where he was previously
                return redirect()->back();
            }

            //If we found the boat let's try to retore it
            try {

                //Restoreing the boat from trashed
                $boat->restore();

            } catch(\Exception $e) {

                return $e->getMessage();
            }

            //Check if we restored the boat
            if( !$boat->trashed() ) {

                //If we could restore the boat successfully send back succes message
                Session::flash('message', 'We successfully restored the boat!');
                Session::flash('class', 'alert-success');

                //Redirect the user list of boats
                return redirect()->route('admin.boats.index');
            }

            //If we couldn't restore the boat send error message to the user
            Session::flash('message', 'Sorry ,We couldn\'t restore the boat!');
            Session::flash('class', 'alert-warning');

            //Redirect the user list of boats
            return redirect()->route('admin.boats.index');

        } else {

            /*
            * If the user doesn't have permission redirect to home page
            */
            return redirect()->route('home');

        }
    }
}
