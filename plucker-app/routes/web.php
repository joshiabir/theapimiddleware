<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\main;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\blog;
use Illuminate\Support\Facades\Response;

Route::get('/', [main::class, 'public']);
Route::post('/', [main::class, 'register'])->name('contact');
Route::get('/test', [main::class, 'test'])->name('test');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard/{domain?}', [main::class, 'dashboard'])->name('dashboard');

    Route::get('/add_domains_get', [main::class, 'add_domains_get'])->name('add_domains_get');
    Route::get('/init', [main::class, 'init'])->name('init');
    Route::post('/init', [main::class, 'init'])->name('init');
    Route::post('/add_domain', [main::class, 'add_domain'])->name('add_domain');
    route::get('/settings/{domain?}', [main::class, 'settings'])->name('settings');
    route::get('/APIs/{domain?}', [main::class, 'apis'])->name('apis');
     route::get('/clusters/{domain?}', [main::class, 'clusters'])->name('clusters');
    route::get('/config/{domain?}', [main::class, 'config'])->name('config');
    route::get('/logs/{domain?}', [main::class, 'logs'])->name('logs');
    route::get('/api/cluster/{domain?}', [main::class, 'api_cluster'])->name('api_cluster');
    route::get('/api/cluster/edit/{domain?}', [main::class, 'api_cluster_edit'])->name('api_cluster_edit');
    route::get('/api/cluster/policy/{domain?}', [main::class, 'api_cluster_policy'])->name('api_cluster_policy');
    route::get('/api/cluster/policy/edit/{domain?}', [main::class, 'api_cluster_policy_edit'])->name('api_cluster_policy_edit');
    route::get('/security/incidents/{domain?}', [main::class, 'incidents'])->name('incidents');
    route::get('/security/investigate/{domain?}', [main::class, 'investigate'])->name('investigate');
    route::get('/logs/investigate/{domain?}', [main::class, 'log_investigate'])->name('log_investigate');
});
Route::get('/auth/redirect/{driver}', function (Request $request) {

    if (!in_array($request->driver, ['google', 'microsoft', 'github'], TRUE)) {
        return response('', 403);
    }
    return Socialite::driver($request->driver)->redirect();
});
Route::get('/auth/callback/{driver}', [main::class, 'auth_callback'])->name('auth_callback');
Route::get('/logger', [main::class, 'logger'])->name('logger');
Route::get('/blog/{slug?}', [main::class, 'blog'])->name('blogs');

Route::get('/sitemap.xml', function () {
    $blogs = blog::select('slug')->where('1', '=', '1')->get();
    return Response::view('sitemap', ['blogs' => $blogs])->header('Content-Type', 'application/xml');
});