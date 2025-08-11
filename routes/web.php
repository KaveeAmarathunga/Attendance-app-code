<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsertypeController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\SuspenseController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceRequestFormController;
use App\Http\Controllers\CompanyController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::resource('leaves', LeaveController::class);
Route::resource('users', UserController::class);
Route::resource('usertypes', UsertypeController::class);
Route::resource('leavetypes', LeaveTypeController::class);
Route::resource('suspenses', SuspenseController::class);
Route::resource('attendances', AttendanceController::class);
Route::resource('attendance_request_forms', AttendanceRequestFormController::class);
Route::resource('companies', CompanyController::class);