<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EligibilityController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/save-eligibility', [EligibilityController::class, 'saveResult'])->name('save.eligibility');
Route::post('/get-ai-suggestion', [EligibilityController::class, 'getSuggestion'])->name('get.ai.suggestion');
Route::get('/get-eligibility-history', [EligibilityController::class, 'getHistory'])->name('get.eligibility.history');
