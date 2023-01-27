<?php

use App\Http\Controllers\AbsentEntryController;
use App\Http\Controllers\AbsentOutController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use App\Models\AbsentEntry;
use App\Models\AbsentOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API route for register new user
Route::post('/register', [AuthController::class, 'register']);

// API route for login user
Route::post('/login', [AuthController::class, 'login']);

// Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Protected APIs
    Route::resource('/user', UserController::class);
    Route::resource('/employee', EmployeeController::class);

    Route::resource('/absent-entry', AbsentEntryController::class);
    Route::post('/absent-entry/employee', [
        AbsentEntryController::class,
        'employee',
    ]);
    Route::post('/absent-entry/download/{absent_entry:id}', [
        AbsentEntryController::class,
        'download',
    ]);

    Route::resource('/absent-out', AbsentOutController::class);
    Route::post('/absent-out/employee', [
        AbsentOutController::class,
        'employee',
    ]);
    Route::post('/absent-out/download/{absent_out:id}', [
        AbsentOutController::class,
        'download',
    ]);

    // API route for logout user
    Route::post('/logout', [AuthController::class, 'logout']);
});
