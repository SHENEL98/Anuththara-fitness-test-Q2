<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/Add-Patient','BackPatientController@add_index')->middleware('auth');
Route::post('/addPatient','BackPatientController@addPatient')->middleware('auth');

Route::get('/Add-Prescriptions','BackPrescriptionsController@prescriptions_index')->middleware('auth');
Route::post('/AddPrescriptions','BackPrescriptionsController@AddPrescriptions')->middleware('auth');
Route::post('/selectPatient','BackPrescriptionsController@selectPatient')->middleware('auth');

Route::get('/track_bill_payments','BackPaymenttController@index')->middleware('auth');
Route::post('/getPaymentBill','BackPaymenttController@getPaymentBill')->middleware('auth');

Route::get('/reports','BackReportController@index')->middleware('auth');
Route::post('/daterange/fetch_report','BackReportController@fetch_report')->name('daterange.fetch_report'); 
Route::get('/pdf_report','BackReportController@pdf_report');   

