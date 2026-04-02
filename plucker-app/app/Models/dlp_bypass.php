<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dlp_bypass extends Model
{
    use HasFactory;

    protected $fillable = ['domain','url'];
}
