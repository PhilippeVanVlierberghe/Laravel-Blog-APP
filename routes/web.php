<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
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
//https://stackoverflow.com/questions/67188884/target-class-postcontroller-does-not-exist-but-it-dose
//'PostController@getIndex'
//Route::get('/', [PostController::class, 'getIndex'])->name('blog.index');
//Route::get('/', 'App\Http\Controllers\PostController@getIndex')->name('blog.index');
Route::get('/', [PostController::class, 'getIndex'])->name('blog.index');

Route::get('post/{id}', [PostController::class, 'getPost'])->name('blog.post');

Route::get('post/{id}/like', [PostController::class, 'getLikePost'])->name('blog.post.like');

Route::get('about', function () {
    return view('other.about');
})->name('other.about');

Route::group(['prefix' => 'admin'], function () {
    Route::get('', [PostController::class, 'getAdminIndex'])->name('admin.index');

    Route::get('create', [PostController::class, 'getAdminCreate'])->name('admin.create');

    //CSRF Attacks
    Route::post('create', [PostController::class, 'postAdminCreate'])->name('admin.edit');

    Route::get('edit/{id}', [PostController::class, 'getAdminEdit'])->name('admin.edit');

    Route::get('delete/{id}', [PostController::class, 'getAdminDelete'])->name('admin.delete');

    Route::post('edit', [PostController::class, 'postAdminUpdate'])->name('admin.update');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
