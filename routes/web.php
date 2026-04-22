<?php

use App\Http\Controllers\DemoController;
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

// Pint Demo Page
Route::get('/pint-demo', function () {
    return view('pint-demo');
});

//  Check Code (Test Only)
Route::get('/run-pint', function () {
    $path = base_path('vendor/bin/pint.bat');

    $output = shell_exec("\"$path\" --test 2>&1");

    if (str_contains($output, 'PASS')) {
        return "<h3 style='color:green'>✅ Code is already clean (PSR-12)</h3><pre>$output</pre>";
    }

    return "<h3 style='color:red'>⚠️ Code issues found</h3><pre>$output</pre>";
});

//  Fix Code (Auto Format)
Route::get('/fix-pint', function () {
    $path = base_path('vendor/bin/pint.bat');

    $output = shell_exec("\"$path\" 2>&1");

    return "<h3 style='color:blue'>🔧 Code formatted successfully</h3><pre>$output</pre>";
});
