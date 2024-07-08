<?php

use Illuminate\Http\Request;
use Laravel\Octane\Facades\Octane;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserDataController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// scribe docs route for octane
Octane::route('GET', '/docs', function () {
    return response()->file(public_path('docs/index.html'));
});

Route::post('user-data/upload', [UserDataController::class, 'upload']);
Route::get('user-data', [UserDataController::class, 'index']);
