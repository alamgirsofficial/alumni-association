<?php

use App\Http\Controllers\AlumniController;
use App\Http\Controllers\AlumniInfoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\AlumniReview;


//  =====================
//  Public Routes 
// =================

Route::inertia('/', 'Welcome')->name('home');



/* 
|-------------------------------------------------------------------------- 
| Authenticated + Approved User Routes 
|-------------------------------------------------------------------------- 
| Only authenticated users whose account has been approved 
| can access the dashboard. 
| */

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::inertia('dashboard', 'Dashboard')->name('dashboard');
// });
Route::middleware(['auth', 'approved',])->group(function () {

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');})->name('dashboard');
});


/* 
|-------------------------------------------------------------------------- 
| Authenticated Routes 
|-------------------------------------------------------------------------- 
| These routes are available to logged-in users, including 
| users waiting for admin approval. 
*/

Route::middleware(['auth'])->group(function () {

// Alumni Information 
Route::get('/alumni-information', [AlumniInfoController::class,'create'])->name('alumni-information.create');
Route::post('/alumni-information', [AlumniInfoController::class,'store'])->name('alumni-information.store');

// Account Status 
// Route::get('/account-status', function () {
//     return Inertia::render('User/AccountStatus');})->name('account-status');

Route::get('/account-status', function () {
    $user = auth()->user();

    $review = AlumniReview::where('user_id', $user->id)
        ->latest()
        ->first();

    return Inertia::render('User/AccountStatus', [
        'status' => $user->status,
        'review' => $review
            ? [
                'action' => $review->action,
                'message' => $review->message,
                'created_at' => $review->created_at,
            ]
            : null,
    ]);
})->name('account-status');


// Alumni Management
Route::get('/alumni', [AlumniController::class, 'index'])->name('alumni.index');

Route::get('/alumni/create', [AlumniController::class, 'create'])->name('alumni.create');
Route::post('/alumni', [AlumniController::class, 'store'])->name('alumni.store');

Route::get('/alumni/pending', [AlumniController::class, 'pending'])->name('alumni.pending');

Route::patch('/alumni/{user}/approve',[AlumniController::class, 'approve'])->name('alumni.approve');
Route::patch('/alumni/{user}/reject',[AlumniController::class, 'reject'])->name('alumni.reject');
Route::patch('/alumni/{user}/pending', [AlumniController::class, 'keepPending'])->name('alumni.pending.update');



Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');



});


require __DIR__ . '/settings.php';
