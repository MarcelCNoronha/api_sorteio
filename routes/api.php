<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClientController;

Route::resource('cliente', ClientController::class)->except(['create', 'edit']);

Route::get('awards', [ClientController::class, 'getAwards']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
