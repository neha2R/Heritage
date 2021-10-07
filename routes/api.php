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
Route::post('update-profile', 'UserController@profile');
Route::post('change-password', 'UserController@change_password');
Route::get('get-profile', 'UserController@get_profile');

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
Route::post('question_media', 'QuestionController@question_media');

      
/**  Feed APi Routes  Start from Here      */
// Get domains according to theme id
Route::get('feed_domains', 'DomainController@getDomainAccordingTheme');
// Feed types all
Route::get('feed_type', 'FeedController@feed_type');
// Feed data according to passing filters
Route::post('feed', 'FeedContentController@feed');
// Save feed to database
Route::post('savefeed', 'FeedContentController@savepost');
Route::get('tagfilter', 'FeedContentController@tagfilter');
// Get feed according to module and collections
Route::post('/module', 'FeedContentController@module');
//Get all saved feeds of user
Route::get('save_feed','FeedContentController@save_feed');
// Filter saved feeds
Route::post('filter_feed','FeedContentController@filter_feed');

 /**  End from Here        */

/**  Product APi Routes  Start from Here      */
Route::get('get_all_products', 'ProductController@get_all_products');
Route::get('product_search', 'ProductController@product_search');
 /**  End from Here        */

// Route::fallback(function () {
//     return response()->json(['message' => 'Not Found.'], 404);
// });
/**  Product APi Routes  Duel Apis from Here      */
Route::post('create_duel', 'DuelController@create_duel');
Route::get('get_all_users', 'DuelController@get_all_users');
Route::get('send_invitation', 'DuelController@send_invitation');
Route::post('accept_invitation', 'DuelController@accept_invitation');
Route::get('generate_link', 'DuelController@generate_link');
 /**  End from Here        */




/**  Tournament APi Routes  Start from Here      */
Route::get('tournament','TournamentController@tournament');

Route::post('tournament_rule','TournamentController@tournament_rule');
Route::post('join_tournament','TournamentController@join_tournament');
Route::post('tournament_questions','TournamentQuestionController@tournament_questions');
Route::post('tournament_result','TournamenetUserController@tournament_result');
Route::post('get_tournament_rank','TournamenetUserController@get_tournament_rank');
Route::post('get_tournament_answer','TournamenetUserController@get_tournament_answer');
Route::get('userleague','TournamenetUserController@userleague');
Route::get('leaguerank','TournamenetUserController@leaguerank');
Route::get('xprewards','TournamenetUserController@xprewards');

/**  End from Here        */

// user report for a quiz 
Route::post('report','UserReportController@userreport');


/*
These are the auth routes
End Here
*/

// For server date and time
Route::get('currentDateTime','UserController@currentDateTime');