<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body',
        'user_id',
        'post_id',
    ];

    /**
     * Get the post record associated with the comment.
     */
    public function post() {

        return $this->belongsTo(Post::class);

    }

    /**
     * Get the user record associated with the comment.
     */
    public function user() {

        return $this->belongsTo(User::class);

    }
}
