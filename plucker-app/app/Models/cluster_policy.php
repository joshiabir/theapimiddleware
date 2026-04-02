<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cluster_policy extends Model
{
    use HasFactory;
    protected $fillable = ['name','owner','host','description','status'];

}
