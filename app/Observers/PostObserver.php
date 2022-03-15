<?php

namespace App\Observers;

use App\Models\Post;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function created(Post $post)
    {
        //
    }

    /**
     * Handle the Post "updated" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function updated(Post $post)
    {
        //
    }

    /**
     * Handle the Post "deleted" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function deleted(Post $post)
    {
        //
    }

    /**
     * Handle the Post "restored" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function restored(Post $post)
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function forceDeleted(Post $post)
    {
        //
    }

    /**
     * Handle the Post "deleting" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function deleting(Post $post)
    {
        //Check if the post has any comment(s)
        if ( $post->comments()->exists() ) {
            //Delete all comment(s) that belongs to the post
            $post->comments()->delete();

        }

        //Check if the post has any photo(s)
        if ( $post->photos()->exists() ) {
            //Delete all photo(s) that belongs to the post
            $post->photos()->delete();

        }
    }

    /**
     * Handle the Post "restoring" event.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function restoring(Post $post)
    {

        //Check if the post has any comment(s)
        if ( $post->comments()->exists() ) {
            //Restore all comment(s) that belongs to the post
            $post->comments()->restore();

        }

        //Check if the post has any photo(s)
        if ( $post->photos()->exists() ) {
            //Restore all photo(s) that belongs to the post
            $post->photos()->restore();

        }
    }
}
