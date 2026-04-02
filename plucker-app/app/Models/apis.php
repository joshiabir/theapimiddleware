<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class apis extends Model
{
    use HasFactory;
    protected $fillable =['api_id', 'host','url','parent_url'];
}
