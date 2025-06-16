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

Route::get('/members/duplicates', [FamilyMemberController::class, 'duplicates'])->name('duplicate_members');
Route::post('/members/duplicates', [FamilyMemberController::class, 'update_duplicate']);

Route::get('/reunion/{reunion}/guest_registration_form', [RegistrationController::class, 'guest_registration'])->name('guest_registration');

Route::get('/past_reunion/{reunion}', [ReunionController::class, 'show_past_reunion'])->name('show_past_reunion');
//Route::get('/reunions/{reunion}/pictures/create', 'ReunionController@create_reunion_pictures')->name('create_reunion_pictures');
//Route::post('/reunion_images_add/{reunion}', 'ReunionController@update_reunion_pictures');
//Route::post('/reunion_image_add/{reunion}', 'ReunionController@update_reunion_image');

/*RESOURCE ROUTE*/
Route::resources([
    'members' => FamilyMemberController::class,
    'registrations' => RegistrationController::class,
    'reunions' => ReunionController::class,
]);

//Route::get('/test', function (){
//
//return view('test');
////  return phpinfo();
//});

require __DIR__.'/auth.php';
