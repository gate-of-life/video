<?php

use App\Http\Controllers\Guest\BasicController;
use App\Http\Controllers\Guest\ChannelController;
use App\Http\Controllers\Guest\VideoController;
use App\Http\Controllers\User\ChannelController as UserChannelController;
use App\Http\Controllers\User\VideoController as UserVideoController;
use App\Http\Controllers\User\UserController;
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

Route::get('/', [BasicController::class, 'index'])->name('welcome');
Route::get('/about', [BasicController::class, 'about'])->name('about');
Route::get('/channels', [ChannelController::class, 'index'])->name('channels.index');
Route::get('/channels/{channel}', [ChannelController::class, 'show'])->name('channels.show');
Route::post('/videos/sort', [VideoController::class, 'sort_video'])->name('videos.sort');
Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
Route::get('/videos/{video}', [VideoController::class, 'show'])->name('videos.show');

Route::middleware(['auth', 'verified'])->prefix('user')->name('user.')->group(function () {
    Route::get('/profile', [UserController::class, 'show_profile'])->name('profile.show');
    Route::get('/profile/edit', [UserController::class, 'edit_profile'])->name('profile.edit');
    Route::post('/profile', [UserController::class, 'update_profile'])->name('profile.update');
    Route::resource('/channels', UserChannelController::class);
    Route::put('/channels/{channel}/change-category', [UserChannelController::class, 'change_category'])->name('channels.change_category');
    Route::get('/videos/select-channel', [UserVideoController::class, 'select_channel'])->name('videos.select_channel');
    Route::post('/videos/filter', [UserVideoController::class, 'filter'])->name('videos.filter');
    Route::get('/videos/filter', [UserVideoController::class, 'index'])->name('videos.index');
    Route::get('/videos/{video}', [UserVideoController::class, 'show'])->name('videos.show');
});

require __DIR__ . '/auth.php';
