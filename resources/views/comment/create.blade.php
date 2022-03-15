<style>
.comment-img {
  width:64px;
  height:64px;
}
</style>

<div class="row p-2">
  <div class="col-md-8">
    <!-- Form section with post method to send the comment to the server side -->
    <div class="media">
      <!-- Currently authenticated user profile picture -->
      <img class="mr-3 comment-img" src="{{ isset(Auth::user()->photos['file']) ? asset(Auth::user()->photos['file']) : 'https://via.placeholder.com/64x64' }}" alt="User's profile picture">
      <!-- -->
      <div class="media-body">
        <!-- Form for sending comment to server -->
        <form class="p-2 mt-3" action="{{ action([App\Http\Controllers\CommentsController::class, 'store']) }}" method="POST">
        <!-- -->
          @csrf
          @method('POST')
          <div class="form-group">

            <!-- Post id -->
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <!-- -->

            <!-- Textarea for the user input -->
            <textarea class="form-control" name="body" placeholder="Join the discussion..." required></textarea>
            <!-- -->

          </div>
          <!-- Sumbit button for the form -->
          <button class="btn btn-info" type="submit">Submit</button>
          <!-- -->
        </form>
      </div>
      <!--  -->
    </div>
  </div>

  <div class="col-md-4">
    <!-- Display message from server side -->
    @include('includes.alert')
    <!--  -->
  </div>

</div>

