<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
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




// Route::apiResource('posts', PostController::class);  
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);


//test
// Route::get('/user',[AuthController::class,'userProfile'])->middleware('auth:api');


//auth apis
Route::middleware(['auth:api'])->group(function (){

    //test api
    Route::get('/user',[AuthController::class,'userProfile']);


    Route::post('/refresh-token',[AuthController::class,'refreshToken']);
    Route::get('/check_is_today_leave',[LeaveController::class,'checkIsTodayLeave']);
    Route::get('/get-my-today-attendance',[AttendanceController::class,'getMyTodayAttendance']);
    Route::get('/get-supervisors',[UserController::class,'getSupervisorsByCompany']);
    Route::post('/apply-leave',[LeaveController::class,'store']);
    Route::get('get-leave-types',[LeaveTypeController::class,'show']);
    Route::get('/get-supervisor',[UserController::class,'getSupervisor']);
    Route::post('/check-in',[AttendanceController::class,'checkIn']);
    Route::post('/check-out',[AttendanceController::class,'checkOut']);
    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('/get-working-days',[AttendanceController::class,'getNumberOfWorkingDays']);
    Route::get('/get-number-of-leaves',[LeaveController::class,'getNumberOfLeaves']);
    Route::get('/leave-summery',[LeaveController::class,'getLeaveSummery']);
    Route::get('/my-leaves',[LeaveController::class,'getMyLeaves']);
    Route::get('/get-leave-requests',[LeaveController::class,'getLeaveRequests']);
    Route::post('/consider-leave-request',[LeaveController::class,'considerLeave']);
    Route::get('/today-leave-list',[LeaveController::class,'getTodatLeaveList']);
    Route::get('/get-today-absentees',[LeaveController::class,'getTodayAbsentees']);
    Route::get('/get-today-attendance-approval-list',[AttendanceController::class,'getCheckinApprovalList']);

});