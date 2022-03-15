<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Table name.
     * @var string
     */
    protected $table = 'reservations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'boat_id',
        'user_id',
        'number_of_passengers',
        'limit',
        'start_of_rent',
        'end_of_rent'
    ];

    public function boats() {

        return $this->belongsTo(Boat::class,'boat_id','id');

    }

    public function users() {

        return $this->belongsTo(User::class);

    }
}
