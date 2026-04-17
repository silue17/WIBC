<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\SiteController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\AchievementController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\GalleryController;

// ── Site public ──────────────────────────────────────────────────────────
Route::get('/', [PageController::class, 'index']);
Route::get('/actualites', [PageController::class, 'newsPage']);
Route::get('/actualites/{id}', [PageController::class, 'newsDetail']);
Route::get('/actualites/{id}/photo', [PageController::class, 'newsPhoto']);

// ── Admin : vues ─────────────────────────────────────────────────────────
Route::prefix('admin')->group(function () {
    Route::get('/',             fn() => view('admin.dashboard'));
    Route::get('/hero',         [SiteController::class, 'heroIndex']);
    Route::get('/about',        [SiteController::class, 'aboutIndex']);
    Route::get('/services',     fn() => view('admin.services'));
    Route::get('/team',         fn() => view('admin.team'));
    Route::get('/achievements', fn() => view('admin.achievements'));
    Route::get('/news',         fn() => view('admin.news'));
    Route::get('/gallery',      fn() => view('admin.gallery'));
    Route::get('/contact',      [SiteController::class, 'contactIndex']);
});

// ── Admin : API JSON ──────────────────────────────────────────────────────
Route::prefix('admin/api')->group(function () {

    // Hero
    Route::get('/hero',  [SiteController::class, 'heroShow']);
    Route::put('/hero',  [SiteController::class, 'heroUpdate']);

    // About
    Route::get('/about', [SiteController::class, 'aboutShow']);
    Route::put('/about', [SiteController::class, 'aboutUpdate']);

    // Contact
    Route::get('/contact', [SiteController::class, 'contactShow']);
    Route::put('/contact', [SiteController::class, 'contactUpdate']);

    // Services
    Route::get('/services',              [ServiceController::class, 'index']);
    Route::post('/services',             [ServiceController::class, 'store']);
    Route::put('/services/{service}',    [ServiceController::class, 'update']);
    Route::delete('/services/{service}', [ServiceController::class, 'destroy']);

    // Team
    Route::get('/team',                  [TeamController::class, 'index']);
    Route::post('/team',                 [TeamController::class, 'store']);
    Route::put('/team/{teamMember}',     [TeamController::class, 'update']);
    Route::delete('/team/{teamMember}',  [TeamController::class, 'destroy']);

    // Achievements
    Route::get('/achievements',                  [AchievementController::class, 'index']);
    Route::post('/achievements',                 [AchievementController::class, 'store']);
    Route::put('/achievements/{achievement}',    [AchievementController::class, 'update']);
    Route::delete('/achievements/{achievement}', [AchievementController::class, 'destroy']);

    // News
    Route::get('/news',           [NewsController::class, 'index']);
    Route::post('/news',          [NewsController::class, 'store']);
    Route::put('/news/{news}',    [NewsController::class, 'update']);
    Route::delete('/news/{news}', [NewsController::class, 'destroy']);

    // Gallery
    Route::get('/gallery',              [GalleryController::class, 'index']);
    Route::post('/gallery',             [GalleryController::class, 'store']);
    Route::delete('/gallery/{gallery}', [GalleryController::class, 'destroy']);
});