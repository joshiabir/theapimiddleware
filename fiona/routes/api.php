<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\main;

Route::any('/{any}', [main::class, 'handleRequest'])->where('any', '.*');