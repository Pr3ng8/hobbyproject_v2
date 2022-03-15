<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'birthdate',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Check if the user has access to a task
     * @return boolean
     */
    public function hasAccess(Array $array) {

        if(count($array) <= 0 || empty($array) || is_null($array)) return false;

        $length = count($array);

        for($i = 0;$i < $length; $i++) {

            if( in_array($array[$i], $this->roles->first()->attributesToArray()) ) return true;

        }

        return false;
    }
    /**
     * Get the role name which the user has
     * @return string
     */
    public function getRole() {

        return $this->roles
            ->first()
            ->attributesToArray()['name'];

    }
    /**
     * Check if the user has permission to make a reservation.
     * @return int
     */
    public function hasPermission() : int
    {
        return $this->status->status;
    }

    /**
     * Get the full name of the user.
     * @return string
     */
    public function getFullName(): string {

        return $this->first_name . ' ' . $this->last_name;

    }

    /**
     * Get the roles record associated with the user.
     */
    public function roles() {

        return $this->belongsToMany(Role::class);

    }

    /**
     * Get the reservations record associated with the user.
     */
    public function reservations() {

        return $this->hasMany(Reservation::class);

    }

    /**
     * Get the posts record associated with the user.
     */
    public function posts() {

        return $this->hasMany(Post::class);

    }

    /**
     * Get the status record associated with the user.
     */
    public function status()
    {
        return $this->hasOne(UserStatus::class);
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
