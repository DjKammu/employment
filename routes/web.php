<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();


Route::get('/', function () {

    if (Auth::user()) { 
        return redirect('/dashboard');
    } 
    return view('frontend.welcome');
});

Route::get('/register',function(){
    return redirect('/');
});

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
    $exitCode = Artisan::call('storage:link', [] );
    echo $exitCode;
});

Route::get('/migration', function () {
    Artisan::call('migrate');
    $exitCode = Artisan::call('migrate', [] );
    echo $exitCode;
});

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])
->name('dashboard');

Route::get('/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('profile');

Route::post('/profile', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('profile');

Route::post('/password', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('password');


Route::get('/setup', [App\Http\Controllers\HomeController::class, 'setup'])->name('setup');

Route::resource('users', App\Http\Controllers\UserController::class)->middleware('can:add_users');
Route::resource('roles', App\Http\Controllers\RoleController::class)->middleware('can:add_users');


Route::resource('companies', App\Http\Controllers\CompanyController::class);
Route::resource('codes', App\Http\Controllers\CodeController::class)
                ->except('create','store');

Route::prefix('companies')->group(function () {

    Route::get('{id}/codes', [App\Http\Controllers\CodeController::class, 'create'])->name('companies.codes.create'); 

    Route::post('{id}/codes', [App\Http\Controllers\CodeController::class, 'store'])->name('companies.codes.store');

    Route::post('{id}/codes/multiple', [App\Http\Controllers\CodeController::class, 'storeMultiple'])->name('companies.codes.store.multiple');
});

Route::get('/code', [App\Http\Controllers\SignController::class, 'getLinks'])->name('code');

Route::get('/template/{id}', [App\Http\Controllers\SignController::class, 'getTemplate'])->name('template');

Route::post('/signdocument', function () {
    return view('frontend.signdocument');
})->name('signdocument');

Route::get('/signcompleted', function () {
    return view('frontend.signcompleted');
})->name('signcompleted');

Route::get('/redirect', function () {
    return view('frontend.redirect');
})->name('redirect');

