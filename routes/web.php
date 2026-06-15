<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\StageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\ParticipantController::class, 'dashboard'])->name('dashboard');
    Route::get('/participant/dashboard', [\App\Http\Controllers\ParticipantController::class, 'dashboard'])->name('participant.dashboard');
    
    // QR Scan & Puzzle Flow
    Route::get('/scan/{identifier}', [\App\Http\Controllers\ScanController::class, 'handle'])->name('scan.handle');
    Route::post('/stage/{stage}/submit', [\App\Http\Controllers\ScanController::class, 'submitAnswer'])->name('stage.submit');
    Route::get('/stage/success', [\App\Http\Controllers\ScanController::class, 'success'])->name('stage.success');
    
    // Leaderboard
    Route::get('/leaderboard', [\App\Http\Controllers\LeaderboardController::class, 'index'])->name('leaderboard');
    
    // Live Polling & Podium
    Route::get('/participant/event-status', [\App\Http\Controllers\ParticipantController::class, 'eventStatus'])->name('participant.status');
    Route::get('/participant/podium', [\App\Http\Controllers\ParticipantController::class, 'podium'])->name('participant.podium');
    Route::get('/participant/leaderboard-partial', [\App\Http\Controllers\LeaderboardController::class, 'partial'])->name('participant.leaderboard_partial');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware('can:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('events', EventController::class);
        Route::post('events/{event}/publish', [EventController::class, 'publishResults'])->name('events.publish');
        Route::post('events/{event}/push-leaderboard', [EventController::class, 'pushLeaderboard'])->name('events.push_leaderboard');
        Route::get('events/{event}/monitor', [EventController::class, 'monitor'])->name('events.monitor');
        Route::post('events/{event}/teams/{team}/revoke/{progress}', [EventController::class, 'revokePoints'])->name('events.revoke_points');
        Route::post('events/{event}/teams/{team}/revoke-all', [EventController::class, 'revokeAllPoints'])->name('events.revoke_all_points');
        Route::resource('events.stages', StageController::class)->shallow();
        
        Route::get('stages/{stage}/puzzle', [\App\Http\Controllers\Admin\QuestionController::class, 'create'])->name('stages.questions.create');
        Route::post('stages/{stage}/puzzle', [\App\Http\Controllers\Admin\QuestionController::class, 'store'])->name('stages.questions.store');

        Route::resource('teams', \App\Http\Controllers\Admin\TeamController::class)->except(['show', 'edit', 'update']);
    });
});

require __DIR__.'/auth.php';
