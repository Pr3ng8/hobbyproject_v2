@can('comment.view')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<style>
.createdate {
  font-size: 12px;
  margin: 0;
}
.error-message {
  color: #E9F1DF;
}
.error-bg {
  background-color: #F23C50;
}

.success-message {
  color: #F1FFFF;
}
.success-bg {
  background-color: #4A9899;
}
</style>

@if(empty($post->comments) || !isset($post->comments) || is_null($post->comments) || !is_object($post->comments) || count($post->comments) <= 0)

  <!-- If we did not found any comment thats belongs to the post we show this message -->
  <div class="row justify-content-md-center">
    <div class="col-md-4">
      <p class="lead">Be the first who comment on this post!</p>
    </div>
  </div>
  <!-- -->


@else
<ul class="list-unstyled">

@foreach($post->comments as $comment)


  <li class="media my-4">

    <!-- Profile picture of the commenter -->
    <img class="mr-3 comment-img" src="{{ isset($comment->user->photos['file']) ? asset($comment->user->photos['file']) : 'https://via.placeholder.com/64x64' }}" alt="The Commenter profile picture">
    <!-- -->

    <div class="media-body">
      <!-- The user name who created the comment -->
      <h5 class="">{{ $comment->user->getFullName() }}</h5>
      <!-- -->

      <!-- The content of the comment -->
      <p name="body">{{ $comment->body }}</p>
      <!-- -->

      <!-- Creation date of the comment -->
      <p name="font-weight-light" class="createdate">{{ $comment->created_at->format('M D o h:m:s') }}</p>
      <!-- -->

      <!-- Check if the user created the comment and allowed to edit or delete the comment -->
      @can('comment.delete',$comment)
      <div class="row">

          <!-- Editing the comment -->
        <div class="col-md-1">
          <form class="editForm"  method="POST">
            @csrf
            @method('GET')
            <input type="hidden" name="id" value="{{ $comment->id }}" />
            <button type="button" class="btn btn-link editbutton" alt="edit button">Edit</button>
          </form>
        </div>
        <!--  -->

      <!-- Deleting the comment -->
      <div class="col-md-1">
        <form action="{{ action([App\Http\Controllers\CommentsController::class,'destroy'], ['id' => $comment->id]) }}" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-link" alt="edit button">Delete</button>
        </form>
      </div>
      <!--  -->

        <!-- Updating the comment -->
        <div class="col-md-1">
          <form class="updateForm"  method="POST" hidden>
            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{ $comment->id }}" readonly/>
            <button type="button" class="btn btn-link updatebutton" alt="edit button">Update</button>
          </form>
        </div>
        <!--  -->

        <!-- Cancel the editing -->
        <div class="col-md-1">
          <form class="cancelForm" method="POST" hidden>
            <input type="hidden" />
            <button type="button" class="btn btn-link cancelbutton" alt="cancel button">Cancel</button>
          </form>
        </div>
        <!--  -->

      </div>
      @endif
      <!-- -->
    </div>

  </li>

@endforeach

</ul>
<script type="text/javascript">
$( document ).ready(function(){

  // Adding click event listener to the edit button
  $( "ul" ).on( "click",'li .editbutton', function() {

      //First we check if there is any active editing by searching for textarea among the comments
      let existsTextArea = $('ul li .media-body').has('textarea');

      // Checking it by the length, if it's greater than 0 it means user clicked already to edit button from the comments.
      if ( existsTextArea.length ) {

        //If the user already clicked to one edit button from another comment we want to do back the editing with the cancelButton function
        existsTextArea.each(cancelButton);

        //Than we let the user edit the comment what he has clicked on after the others
        $(this).each(editButton);

      } else {

        //If the user didn't begin editing any comment before we just simply let him edit the comment by calling the editButton function
        $(this).each(editButton);

      }

  });

  // Adding click event listener to the cancel button
  $( "ul" ).on( "click",'li .cancelbutton', cancelButton);

  // Adding click event listener to the update button
  $( "ul" ).on( "click",'li .updatebutton', updateButton);


  /*
  * We are collecting the data that we wnat to send to the server side.
  */
  function updateButton() {
        //Creating an empty obj
        let hiddenInputValue = [];

        //Let's find the the media-body which contains the forms and the comment
        let mediaBody = $(this).closest('.media-body');

        //We are trying to get the edited comment from textarea
        let content = mediaBody.find('textarea').val();

        //Get the required data from the updateForm's hidden inputs and assign it to hiddenInputValue
        $(this).closest('.updateForm').find('input').each(function () {

          hiddenInputValue[$(this).attr("name")] = $(this).val();

        });

        //Then assign the content of the textarea to the variable we are sending to the server side
        hiddenInputValue["body"] = content.length > 0 ? content : "NULL";

        //Callign function to send data to server side
        sendDataToEdit(hiddenInputValue);
  }

  /*
  * In this function we are creating an edit field by removing the p tag from the media-body
  * which contained the comment we want to edit. Before we delete the p tag we get the text inside it
  * then assign it to a hidden input field inside cancelForm in case we change our minds.
  * After all we insert the textarea after the h5 tag with the value of the p tag we deleted.
  */
  function editButton () {

    hideErrorMessages(['success','error']);

    //Let's find the the media-body which contains the forms and the comment
    let mediaBody = $(this).closest('.media-body');

    reverseButtonViibility(mediaBody);

    //In this media-body lets find the comment and get the text from it
    let content = mediaBody.find('p[name="body"]').text();

    //Lets find the hidden input where we wnat to put the text as backup
    let backUpText = mediaBody.find('.cancelForm input[type=hidden]');

    //Assign the value of the content to the hidden input field in the cancelForm
    backUpText.val(content);

    //Let's remove the p tag which conatins the comment in the media-body
    mediaBody.find('p[name="body"]').remove();

    //Create a textfield and assign the value of the comment what we have got from p tag as content
    let textArea = $('<textarea class="form-control" required></textarea>').val(content);

    //Find the media-body h5 tag and assign it ti h5Tag var
    let h5Tag = mediaBody.find('h5');

    //Let's insert the textarea we have created before and insert it after the h5Tag
    textArea.insertAfter(h5Tag);

  }

  /*
  * In this function we remove the textarea what we have inserted in
  * with the editButton function.After that we get the original comment form
  * editForm nad creat p tag and add the text to the p tag and isert it into back.
  *
  */
  function cancelButton () {

    hideErrorMessages(['success','error']);

    //First find the comment we were editing
    let mediaBody = $(this).closest('.media-body');

    //Remove the textarea from that media-body where we are canceling the editing
    mediaBody.find('textarea').remove();

    //Secondly find this medai-body cancelForm that has the original text in hidden inpput
    let content = mediaBody.find('.cancelForm input[type=hidden]').val();

    //Create a p Tag and add the value of the original text
    let pTag = $('<p name="body"></p>').text(content);

    //Find the h5 tag so we can isnert after the p tag
    let h5Tag = mediaBody.find('h5');

    //Let's put back the p tag where it was originally inside the media-body after the h5 tag
    pTag.insertAfter(h5Tag);

    reverseButtonViibility(mediaBody);

  }

  /**
  * If the edit button is visible we want to hide it
  * But if the update and cancel button visible then we want
  * to hide them and make dit button visible
  **/
  function reverseButtonViibility(mediaBody) {

    let attr = mediaBody.find('.editForm').attr('hidden');

    switch (attr) {

      //If the editForm button has not got hidden attribute than this is true
      case false:
      case undefined :

        mediaBody.find('.cancelForm').attr("hidden",false);
        mediaBody.find('.updateForm').attr("hidden",false);
        mediaBody.find('.editForm').attr("hidden",true);

        break;

      //If the editForm button has got hidden attribute then this is true
      case "hidden" :

        mediaBody.find('.cancelForm').attr("hidden",true);
        mediaBody.find('.updateForm').attr("hidden",true);
        mediaBody.find('.editForm').attr("hidden",false);

        break;

    }

  }

  /**
   * Hide error messages
   */
    function hideErrorMessages (messageStyles) {

      for (let i = 0; i < messageStyles.length; i++)
      {

        //Find all error boxes
        let messages = $('ul li .media-body').has('.'+messageStyles[i]+'-bg');

        //Check if is there any
        if ( messages.length > 0 ) {
          //If there is we want to hide all of them
          messages.each(function () {
            $('.'+messageStyles[i]+'-bg', this).remove();
          });
        }

      }

    }
  /*
  * In this function we are sending the eddited comment we would like to update
  */
  function sendDataToEdit (comment) {

    //ajax function for sending data
    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      //Url wehere we want to send the data
      url: "{{ URL::asset('comments') }}/" + comment['id'],
      //Method of the sending
      method: "PUT",
      //The data type we ant to send
      dataType: 'json',
      //the data we want to send to server side
      data: {
        body : comment['body'],
        _token : comment['_token'],
        _method : "PUT",
      },
    }).done(function(response) {
      //If there was no failure we want to insert the comment in the right place
      let editingComment = $('ul li .media-body');

      let textarea = editingComment.find('textarea');

      if ( response.result ) {

        showMessageToUser("Update was success!","success");

        //Creating a p tag for the new comment
        $('<p></p>').attr({
            name: "body"
        })
        //Adding the new coment text to the p tag
        .text(response.comment.body)
        //Let's insert the new comment before the textarea in the comment section
        .insertBefore(textarea);

          //After we insert the p tag we delete the text area
          textarea.remove();

      } else {

        showMessageToUser("Sorry something went wrong!","error");

      }

      reverseButtonViibility(editingComment);

    }).fail(function(response) {
        //If there was a failure we want to get the error messages
        let errors = response.responseJSON;

        //We want to hide the error messages if there was any
        hideErrorMessages(['success','error']);

        //Let's display all the error messages
        for(let i = 0; i < errors.errors['body'].lenght; i++) {

          //Creating wraper thats contains the error message
          showMessageToUser(errors.errors['body'][i], error);

        }
    });

    function showMessageToUser(message,style) {

        $('<div></div>').attr({
        class : "col-12 "+style+"-bg my-1 mx-0 rounded"
        })
        .append(
          //P tag thats contains the error message
          $('<p></p>').attr({
            class: style+"-message"
          })
          .text(message)
        )
        //Insert it after the textarea where we edit the comment
        .insertAfter('ul li .media-body textarea');

    }

  }

});
</script>
@endif

@endcan
