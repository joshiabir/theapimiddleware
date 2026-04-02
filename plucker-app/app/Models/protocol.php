<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class protocol extends Model
{
    use HasFactory;

    protected $fillable = ['host','method'];
}
