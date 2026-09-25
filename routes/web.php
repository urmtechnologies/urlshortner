<?php
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InviteController as TeamInviteController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Auth\AcceptInvitationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\SuperAdmin\ClientController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperDashboardController;
use App\Http\Controllers\SuperAdmin\Invitecontroller as ClientInviteController;
use Illuminate\Support\Facades\Route;
Route::get('/', fn () => redirect()->route('login'));
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');
    Route::get('/invitation/{token}', [AcceptInvitationController::class, 'show'])->name('invitation.accept');
    Route::post('/invitation/{token}', [AcceptInvitationController::class, 'store'])->middleware('throttle:5,1')->name('invitations.accept.store');
});
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
Route::middleware(['auth','superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperDashboardController::class, 'index'])->name('dashboard');
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::get('/invite', [ClientInviteController::class, 'index'])->name('invite.index');
    Route::post('/invite', [ClientInviteController::class, 'store'])->middleware('throttle:5,1')->name('invite.store');
    Route::get('/short-urls', [ShortUrlController::class, 'index'])->name('short-urls.index');
    Route::get('/short-urls/download', [ShortUrlController::class, 'download'])->name('short-urls.download');
});
Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/members', [TeamController::class, 'index'])->name('members.index');
    Route::get('/invite', [TeamInviteController::class, 'index'])->name('invite.index');
    Route::post('/invite', [TeamInviteController::class, 'store'])->middleware('throttle:5,1')->name('invite.store');
    Route::get('/short-urls', [ShortUrlController::class, 'index'])->name('short-urls.index');
    Route::get('/short-urls/download', [ShortUrlController::class, 'download'])->name('short-urls.download');
    Route::get('/short-urls/create', [ShortUrlController::class, 'create'])->name('short-urls.create');
    Route::post('/short-urls', [ShortUrlController::class, 'store'])->middleware('throttle:20,1')->name('short-urls.store');
});
Route::middleware(['auth','member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
    Route::get('/short-urls', [ShortUrlController::class, 'index'])->name('short-urls.index');
    Route::get('/short-urls/download', [ShortUrlController::class, 'download'])->name('short-urls.download');
    Route::get('/short-urls/create', [ShortUrlController::class, 'create'])->name('short-urls.create');
    Route::post('/short-urls', [ShortUrlController::class, 'store'])->middleware('throttle:20,1')->name('short-urls.store');
});
Route::get('/s/{code}', [ShortUrlController::class, 'resolve'])->where('code', '[A-Za-z0-9]{7,12}')->name('short.resolve');
