<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;


// Events (basic resource)
Route::get('/events', [EventController::class, 'index']);
Route::post('/events', [EventController::class, 'store']);
Route::get('/events/{event}', [EventController::class, 'show']);
Route::put('/events/{event}', [EventController::class, 'update']);
Route::delete('/events/{event}', [EventController::class, 'destroy']);

// Attendees nested under events
Route::get('/events/{event}/attendees', [AttendeeController::class, 'index']);
Route::post('/events/{event}/attendees', [AttendeeController::class, 'store']);
Route::get('/events/{event}/attendees/{attendee}', [AttendeeController::class, 'show']);
Route::put('/events/{event}/attendees/{attendee}', [AttendeeController::class, 'update']);
Route::delete('/events/{event}/attendees/{attendee}', [AttendeeController::class, 'destroy']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




