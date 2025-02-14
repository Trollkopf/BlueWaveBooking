<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HammockSpace extends Model {
    use HasFactory;

    protected $fillable = ['row', 'col', 'hammocks'];
}
