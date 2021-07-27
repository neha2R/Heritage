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

Route::get('/home', 'HomeController@index')->name('home');
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::resource('/domain', 'DomainController');
    Route::post('/sub-domain-status', 'DomainController@addsubdomain')->name('addsubdomain');
    Route::get('/sub-domain-status/{id}', 'DomainController@changeSubDomainStatus')->name('changesubdomain');
    Route::put('/subdomain/{id}', 'DomainController@updatesubdomain')->name('subdomain');
    Route::delete('/subdomain/{id}', 'DomainController@deletesubdomain')->name('subdomain');
    Route::resource('/agegroup', 'AgeGroupController');

});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
