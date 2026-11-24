<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Portal;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public website
|--------------------------------------------------------------------------
*/
Route::get('/', HomeController::class)->name('home');

Route::controller(PageController::class)->group(function () {
    Route::get('/about', 'about')->name('about');
    Route::get('/about/leadership', 'leadership')->name('about.leadership');
    Route::get('/about/{page:slug}', 'show')->name('pages.show');
    Route::get('/membership', 'membership')->name('membership');
    Route::get('/get-involved', 'getInvolved')->name('get-involved');
    Route::get('/privacy-policy', 'privacy')->name('privacy');
});

Route::get('/our-work', [ProgrammeController::class, 'index'])->name('programmes.index');
Route::get('/our-work/{programme:slug}', [ProgrammeController::class, 'show'])->name('programmes.show');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{post:slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
Route::get('/resources/{resource:slug}', [ResourceController::class, 'show'])->name('resources.show');
Route::get('/resources/{resource:slug}/download', [ResourceController::class, 'download'])->name('resources.download');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');

Route::get('/search', SearchController::class)->name('search');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:6,1');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:6,1');
    Route::get('/forgot-password', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'email'])->middleware('throttle:6,1')->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Member portal
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('portal')->name('portal.')->group(function () {
    Route::get('/', Portal\DashboardController::class)->name('dashboard');
    Route::get('/announcements', [Portal\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/{announcement}', [Portal\AnnouncementController::class, 'show'])->name('announcements.show');
    Route::get('/profile', [Portal\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [Portal\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [Portal\ProfileController::class, 'password'])->name('password.change');
});

/*
|--------------------------------------------------------------------------
| Administration dashboard
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'can:access-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');

    Route::resource('posts', Admin\PostController::class)->except('show');
    Route::resource('events', Admin\EventController::class)->except('show');
    Route::resource('resources', Admin\ResourceController::class)->except('show');
    Route::resource('programmes', Admin\ProgrammeController::class)->except('show');
    Route::resource('pages', Admin\PageController::class)->except('show');
    Route::resource('announcements', Admin\AnnouncementController::class)->except('show');
    Route::resource('categories', Admin\CategoryController::class)->except(['show', 'create', 'edit']);
    Route::resource('people', Admin\PersonController::class)->except('show');

    Route::resource('messages', Admin\MessageController::class)->only(['index', 'show', 'destroy']);
    Route::resource('media', Admin\MediaController::class)->only(['index', 'store', 'destroy'])->parameters(['media' => 'media']);

    Route::get('settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');

    Route::middleware('can:manage-users')->group(function () {
        Route::resource('users', Admin\UserController::class)->only(['index', 'edit', 'update', 'destroy']);
        Route::patch('users/{user}/approve', [Admin\UserController::class, 'approve'])->name('users.approve');
    });
});
