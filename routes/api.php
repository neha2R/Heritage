<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::get('country', 'StatesController@index');
Route::get('state/{id}', 'StatesController@fetchState');
Route::get('city/{id}', 'StatesController@fetchCity');
Route::post('stepone', 'UserController@stepone');
Route::post('createquiz', 'AttemptController@store');
Route::get('domains', 'DomainController@domains');
Route::get('difficulty', 'DifficultyLevelController@difficulty');
Route::get('speed', 'QuizSpeedController@speed');
Route::post('email_verify', 'UserController@email_verify');
Route::post('questions', 'QuestionController@question');
Route::post('quiz_rule', 'QuizRuleController@quiz_rules');
