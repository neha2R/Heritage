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
// Email verification
Route::post('email_verify', 'UserController@email_verify');


/*
These are the auth routes
Start from here
*/
Route::post('createquiz', 'AttemptController@store');
Route::get('domains', 'DomainController@domains');
Route::get('difficulty', 'DifficultyLevelController@difficulty');
Route::get('speed', 'QuizSpeedController@speed');

// Question for quiz
Route::post('questions', 'QuestionController@question');
//Quiz Rules before exam start
Route::post('quiz_rules', 'QuizRuleController@quiz_rules');
// Save Exam result of quiz
Route::post('save_result', 'AttemptController@saveresult');
// Result of exam
Route::post('get_result', 'AttemptController@get_result');
// Answer after exam submission
Route::post('get_answerkey', 'AttemptController@get_answerkey');
// Help & Support Api
Route::post('helpandsupport', 'HelpAndSupportController@store');
// Get all themes
Route::get('theme', 'ThemeController@getAllThemes');
// Get domains according to theme id
Route::get('feed_domains', 'DomainController@getDomainAccordingTheme');
// Feed types all
Route::get('feed_type', 'FeedController@feed_type');
// Feed data according to passing filters
Route::post('feed', 'FeedContentController@feed');

Route::post('savefeed', 'FeedContentController@savepost');
Route::get('tagfilter', 'FeedContentController@tagfilter');

/*
These are the auth routes
End Here
*/

// Route::fallback(function () {
//     return response()->json(['message' => 'Not Found.'], 404);
// });
