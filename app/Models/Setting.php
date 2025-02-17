<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'morning_start',
        'morning_end',
        'afternoon_start',
        'afternoon_end',
        'full_day_start',
        'full_day_end',
        'price_morning',
        'price_afternoon',
        'price_full_day',
        'closed_from',
        'closed_to'
    ];
}
