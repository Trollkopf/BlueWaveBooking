<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hammock extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'status', 'location'];
}
