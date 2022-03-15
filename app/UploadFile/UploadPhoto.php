<?php

namespace App\UploadFile;

use Session;
use App\{Photo};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth};


class UploadPhoto {
    /*
    * If the user wnats to upload photo we need to check if its
    * fit to the conditions. If it does, than we need to create a unique name for
    * it and upload it to 'images/' folder. If we could upload it we insert it in the database.
    */

    //Set default file extension whitelist
    private const whitelist_ext = [
        'jpeg',
        'jpg',
        'png',
        'bmp'
    ];

    //Set default file type whitelist
    private const whitelist_type = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/bmp'
    ];

    //Set default file type whitelist
    private const fileMaxSize = 16000000;

    /**
     * Upload photo
     * @param  \Illuminate\Http\Request  $request
     */
    public function upload(Request $request, $model) {

        if ( $this->validateFile($request) ) {

            $name = $this->createUniqueName($request);

            if ( $this->checkIfNameIsTaken($name) ) {
                
                $this->uploadPhoto($request, $name);
                
                $this->insertToDatabase($model, $name);

                return true;

            } else {

                return false;

            }
        }

        return false;
    }

    /**
     * Upload the phot to the folder
     * 
     * @param  \App\Http\Requests\Request $request
     * @param  string $name
     */
    private function uploadPhoto(Request $request, $name) {
        try {
            //Trying to upload photo to the 'images/' folder

            //Get the photo from request
            $request->file('file')
            //Call the move function to upload it
            ->move(
                //Specify the folder where we want to upload
                'images/',
                //Rename the file we have created before
                $name
            );

        } catch(\Exception $e) {

            throw new \Exception( $e->getMessage() );
        }
    }

    /**
     * Insert the name of the photo and the model type to the database
     * 
     * @param Model  $model
     * @param string $name
     * @return mixed 
     */
    private function insertToDatabase($model, $name) {

        if ( $this->checkIfModelHasPhoto($model) ) {

            $this->insertNewPhotoDatabase($model, $name);

        } else {

            $this->replacePhotoInDatabase($model, $name);

        }
    }

    /**
     * Insert name of the photo in databse if tthe model does not have one
     * 
     * @param Model  $model
     * @param string $name
     * @return mixed 
     */
    private function insertNewPhotoDatabase($model, $name) {

        try{
            //Inserting the photo to the databse
            $model->photos()->save(new Photo(['file' => $name]));

        } catch(\Exception $e) {

            throw new \Exception( $e->getMessage() );
        }
    }

    /**
     * Replace the old photo name in the database with the new one
     * 
     * @param Model  $model
     * @param string $name
     * @return mixed 
     */
    private function replacePhotoInDatabase($model, $name) {

        try{
            //Updateing the photo in the databse
            $model->photos()->update(['file' => $name]);

        } catch(\Exception $e) {

            throw new \Exception( $e->getMessage() );
        }
    }

    /**
     * Check if the model has photo in database
     * @param Model  $model
     * @return boolean 
     */
    private function checkIfModelHasPhoto($model) {

        try{
            //Inserting the photo to the databse
            $check = $model->photos['file'];

        } catch(\Exception $e) {

            throw new \Exception( $e->getMessage() );
        }
        
        return is_null($check) || empty($check) ? true : false;
    }




    /**
     * Check the file is valid
     * @param \Illuminate\Http\Request  $request
     */
    private function validateFile(Request $request) {

        $this->checkFileExtension($request);
        $this->checkFileType($request);
        $this->checkFileSize($request);

        return $this->checkIfValidationFailed();
    }

    /**
     * Check if there is any error in the session
     * @return boolean
     */
    private function checkIfValidationFailed() {

        return Session::has('type') || Session::has('size') || Session::has('extension') ?  false : true;

    }

    /**
     * Check the file extension is valid
     * @param \Illuminate\Http\Request  $request
     */
    private function checkFileExtension(Request $request) {
        //Check file has the right extension           
        if ( !in_array( $request->file('file')->getClientOriginalExtension(), self::whitelist_ext )) {
            Session::flash('extension', 'Invalid file Extension!');
        }
    }

    /**
     * Check the file type is valid
     * @param \Illuminate\Http\Request  $request
     */
    private function checkFileType(Request $request) {
        //Check that the file is of the right type
        if ( !in_array( $request->file('file')->getClientMimeType(), self::whitelist_type )) {
            Session::flash('type', 'Invalid file Type!');
        }
    }

    /**
     * Check the file size is correct
     * @param \Illuminate\Http\Request  $request
     */
    private function checkFileSize(Request $request) {
        //Check that the file is not too big, max 16mb
        if ( $request->file('file')->getClientSize() >= self::fileMaxSize) {
            Session::flash('size', 'File is too big!');
        }
    }

    /**
     * Creatign new unique name for the file
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    private function createUniqueName(Request $request) {

        //creating new unique name for photo
        $tmp = str_replace( array('.',' '), array('',''), microtime() );

        /*
        * Creating a temporary name to the photo we want to upload.
        * We want to recive the pohot original anme form $request by
        * calling on it the getClientOriginalName. Then we convert the string in lower case.
        * Then we explode it at '.' getting rid off from the type of the photo.
        */
        $tmp_name = explode('.', strtolower( $request->file('file')->getClientOriginalName() ) );

        /* 
        * With uniqid() we create a unique id and prepending the $tmp to it.
        * Append to it the currently authenticated user id sepereated by '_'.
        * After appending the user id we append the file original name converted into lowercase and we seperate them with '_'.
        * And finally we append the file extention with '.' after the name.
        */
        $newname = uniqid($tmp) . '_' . Auth::id() . '_' . $tmp_name[0] . '.'  . $request->file('file')->getClientOriginalExtension();

        return $newname;

    }

    /**
     * Check if the name is already taken
     * @param string $name
     * @return boolean 
     */
    private function checkIfNameIsTaken($name)
    {
        return $this->checkDatabaseIfNameIsTaken($name) || $this->checkFolderIfNameIsTaken($name) ?  false : true;
    }
    
    /**
     * Check if the name is already taken in the database
     * @param string $name
     * @return boolean 
     */
    private function checkDatabaseIfNameIsTaken($name)
    {
        //Let's try to check if the name already exits in database
        try {
            //Checking if the there is any file with the same name
            $check = Photo::where('file','=',$name)->exists();

        } catch(\Exception $e) {

            throw new \Exception( $e->getMessage() );
        }

        return $check;
    }

    /**
     * Check if the name is already taken in the folder
     * @param string $name
     * @return boolean 
     */
    private function checkFolderIfNameIsTaken($name)
    {
        return file_exists('images/' . $name);
    }


}