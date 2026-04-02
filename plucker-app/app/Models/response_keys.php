<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class response_keys extends Model
{
    use HasFactory;

    protected $fillable= ['host','url','method','keys'];
}
