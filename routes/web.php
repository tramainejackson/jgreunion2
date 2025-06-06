<?php

use App\Http\Controllers\FamilyMemberController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ReunionController;
use App\Mail\Registration_Admin;
use Illuminate\Support\Facades\Mail;
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

Route::get('/', [HomeController::class, 'index'])->name('guest_home');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

/*RESOURCE ROUTE*/
Route::get('/members/duplicates', [FamilyMemberController::class, 'duplicates'])->name('duplicate_members');
Route::post('/members/duplicates', [FamilyMemberController::class, 'update_duplicate']);
Route::resource('members', FamilyMemberController::class);

/*RESOURCE ROUTE*/
Route::get('/reunion/{reunion}/guest_registration_form', [RegistrationController::class, 'guest_registration'])->name('guest_registration');
//Route::put('/registrations/{registration}/add_registration_member', 'RegistrationController@add_registration_member')->name('add_registration_member');
//Route::delete('/remove_reg_member/{registration}/{remove_ind_member}', 'RegistrationController@remove_ind_member')->name('remove_ind_member');
Route::resource('registrations', RegistrationController::class);

/*RESOURCE ROUTE*/
Route::get('/past_reunion/{reunion}', [ReunionController::class, 'show_past_reunion'])->name('show_past_reunion');
//Route::get('/reunions/{reunion}/pictures/create', 'ReunionController@create_reunion_pictures')->name('create_reunion_pictures');
//Route::post('/reunion_images_add/{reunion}', 'ReunionController@update_reunion_pictures');
//Route::post('/reunion_image_add/{reunion}', 'ReunionController@update_reunion_image');
//Route::post('/reunion_hotel_image_add/{reunion}', 'ReunionController@update_hotel_image');
//Route::delete('/reunion_events/{reunion_event}', 'ReunionController@remove_event')->name('remove_event');
//Route::delete('/reunion_committee_members/{reunion_committee}', 'ReunionController@remove_committee_member')->name('remove_committee_member');
Route::resource('reunions', ReunionController::class);

//Route::get('/test', function (){
//
//return view('test');
////  return phpinfo();
//});

require __DIR__.'/auth.php';
