<?php

use App\Http\Controllers\DemoController;
use App\Http\Controllers\PintController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Demo controller routes
Route::get('/test', [DemoController::class, 'test']);
Route::get('/bad', [DemoController::class, 'badCode']);
Route::get('/clean', [DemoController::class, 'cleanCode']);

// Enhanced Pint Routes
Route::get('/pint-demo', [PintController::class, 'dashboard']);
Route::get('/pint/check', [PintController::class, 'checkCode']);
Route::post('/pint/fix', [PintController::class, 'fixCode']);
Route::post('/pint/create-test-file', [PintController::class, 'createTestFile']);
Route::delete('/pint/delete-test-file', [PintController::class, 'deleteTestFile']);
Route::get('/pint/stats', [PintController::class, 'getStats']);