<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactDataController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FileController;

use App\Models\User;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

Route::get('/home', function () {
    return Inertia::render('AuthenticatedLayout');
})->middleware(['auth', 'verified'])->name('home');

Route::middleware('auth')->group(function () {
    //Auth user profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Resources group
    Route::resource('contact-data', ContactDataController::class);
    Route::resource('users', UserController::class);
    Route::resource('posts', PostController::class);
    Route::resource('files', FileController::class);
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/timeline', [UserController::class, 'getUserTimeLinePosts'])->name('users.timeline');
    Route::get('/contacts', [UserController::class, 'getContacts'])->name('contacts.index');
    Route::get('/contacts/search/{search_string?}', [UserController::class, 'searchContacts'])->name('contacts.search');
    Route::get('/search/users/{search_string?}', [UserController::class, 'searchUsers'])->name('users.search');
    Route::post('/users/contacts/add', [UserController::class, 'addContact'])->name('users.contacts.add');

    //User files
    Route::get('/files/user/{id}', [FileController::class, 'getUserFiles'])->name('files.user');

    //Post interactions
    Route::post('/posts/{post}/like', [PostController::class, 'likePost'])->name('posts.like');
    Route::post('/posts/{post}/repost', [PostController::class, 'repostPost'])->name('posts.repost');
    Route::post('/posts/{post}/comment', [PostController::class, 'commentPost'])->name('posts.comment');
});

require __DIR__.'/auth.php';
