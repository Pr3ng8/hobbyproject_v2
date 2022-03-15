<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory,SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body',
        'user_id',
        'deletedBy_userId'
    ];

    public function user() {

        return $this->belongsTo(User::class);

    }

    /**
     * Get the photos record associated with the user.
     */
    public function photos() {

        return $this->morphOne(Photo::class,'imageable');

    }

    /**
     * Get the comments record associated with the user.
     */
    public function comments() {

        return $this->hasMany(Comment::class);

    }
}
