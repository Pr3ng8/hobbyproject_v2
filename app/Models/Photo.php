<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file',
    ];

    /**
     * The attributes that tells in which folder we put the photos
     *
     * @var string
     */
    private const folder = 'images/';

    /**
     * Get all of the owning imageable models.
     * The  imageable_type column is how the ORM determines which "type"
     * of owning model to return when accessing the imageable relation.
     */
    public function imageable() {

        return $this->morphTo();

    }

    /**
     * When we are retrieving the file attribute we ant to attacht the folder to it.
     *
     */
    public function getfileAttribute($file) {

        return self::folder . $file;

    }
}
