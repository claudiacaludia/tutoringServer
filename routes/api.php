<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TopicController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/login', [AuthController::class, 'login']);


Route::get('/appointments', [AppointmentController::class, 'index']);
Route::get('/appointments/open', [AppointmentController::class, 'showOpenAppointments']);
Route::get('appointments/{id}', [AppointmentController::class, 'findById']);
Route::get('/subjects', [SubjectController::class, 'index']);
Route::get('/topics', [TopicController::class, 'index']);

Route::get('/userRole/{id}', [AuthController::class, 'getUserRole']);



Route::group(['middleware' => ['api', 'auth.jwt']], function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => ['api', 'auth.jwt']], function () {
    Route::get('/appointments/tutor/{status}/{user}', [AppointmentController::class, 'showTutorAppointmentsConformed']);

   /* Route::get('/appointments/{user}', [AppointmentController::class, 'showOwnAppointments']);*/

    Route::get('/appointments/student/{user}', [AppointmentController::class, 'showStudentAppointments']);
    Route::get('/appointments/tutor/{user}', [AppointmentController::class, 'showTutorAppointments']);
    Route::post('/appointment', [AppointmentController::class, 'saveAppointment']);//auch für studis weil diese Appointment Anfragen stellen können

    Route::put('/appointment/{id}', [AppointmentController::class, 'updateAppointment']); //student kann appointment requesten

    //nur für Tutor*innen aufrufbar
    Route::group(['middleware' => ['api', 'auth.tutor']], function () {
        Route::post('/subject', [SubjectController::class, 'saveSubject']);
        Route::post('/topic', [TopicController::class, 'saveTopic']);

        Route::put('/subject/{id}', [SubjectController::class, 'updateSubject']);
        Route::put('/topic/{id}', [TopicController::class, 'updateTopic']);

        Route::delete('/appointment/{id}', [AppointmentController::class, 'deleteAppointment']);
        Route::delete('/subject/{id}', [SubjectController::class, 'deleteSubject']);
        Route::delete('/topic/{id}', [TopicController::class, 'deleteTopic']);
    });
});
