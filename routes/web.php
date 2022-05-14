<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController;
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
//for midhatter


Route::get('full-calender', [CalendarController::class, 'index']);

Route::post('full-calender/action', [CalendarController::class, 'action']);
//getStuffByService

Route::post('get-staffs', [CalendarController::class, 'getStuffByService']);


Route::get('event/index', [CalendarController::class, 'index'])->name('event.index');
Route::post('event', [CalendarController::class, 'store'])->name('event.store');
Route::patch('event/update/{id}', [CalendarController::class, 'update'])->name('event.update');
Route::delete('event/destroy/{id}', [CalendarController::class, 'destroy'])->name('event.destroy');






//Route for booking
Route::get('/admin/calendar','App\Http\Controllers\CalendarController@index')->name('calendarShow');
//Route::get('/admin/calendar/{staff_id}', 'SalonHomeController@calendarShow');
Route::get('/test','App\Http\Controllers\CalendarController@test');

Route::get('calendar/index', [CalendarController::class, 'index'])->name('calendar.index');
Route::post('admin/calendar/store', [CalendarController::class, 'store'])->name('calendar.store');
Route::patch('calendar/update/{id}', [CalendarController::class, 'update'])->name('calendar.update');
Route::delete('calendar/destroy/{id}', [CalendarController::class, 'destroy'])->name('calendar.destroy');
//SalonHomeController
//http://127.0.0.1:8000/admin/calendar
//salon setting Rout
Route::get('/salon/setting', 'App\Http\Controllers\SalonHomeController@salonSettingShow')->name('sHome');
Route::post('/savesalonsetting','App\Http\Controllers\SalonHomeController@saveSetting')->name('salon_setting');

Route::resource('admin/times', 'App\Http\Controllers\SalonDaysController', [
    'except' => [
        'create'
    ]
]);

//getStaffTimeSlote

Route::post('admin/getStaffbyservice', [CalendarController::class, 'getStaffbyservice'])->name('getStaffbyservice');
Route::post('admin/getStaffTimeSlote', [CalendarController::class, 'getStaffTimeSlote'])->name('getStaffTimeSlote');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
