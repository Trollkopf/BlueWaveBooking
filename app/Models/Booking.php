<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'hammock_id',
        'date',
        'time_slot',
        'status',
        'price',
        'comments',
        'type',
        'name',
        'email',
        'phone'
    ];

    /**
     * Relación con el usuario que realizó la reserva.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con la hamaca reservada.
     */
    public function hammock()
    {
        return $this->belongsTo(HammockSpace::class, 'hammock_id');
    }
}
