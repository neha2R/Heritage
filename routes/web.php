<?php

use Illuminate\Support\Facades\Route;
use App\Faq;

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
    Route::get('/users', 'UserController@index');
    Route::post('/sub-domain-status', 'DomainController@addsubdomain')->name('addsubdomain');
    Route::get('/sub-domain-status/{id}', 'DomainController@changeSubDomainStatus')->name('changesubdomain');
    Route::put('/subdomain/{id}', 'DomainController@updatesubdomain')->name('subdomain');
    Route::delete('/subdomain/{id}', 'DomainController@deletesubdomain')->name('subdomain');
    Route::resource('/agegroup', 'AgeGroupController');
    Route::resource('/difflevel', 'DifficultyLevelController');
    Route::resource('/quizspeed', 'QuizSpeedController');
    Route::resource('/quiztype', 'QuizTypeController');
    Route::resource('/question', 'QuestionController');
    Route::view('/form_bulk','question.UploadBulk');
    Route::post('/upload_bulk','QuestionController@import')->name('upload_bulk');
    Route::resource('/faq', 'FaqController');
    Route::resource('/quizrules', 'QuizRuleController');
    Route::get('/get_rule_type/{id}', 'QuizRuleController@get_rule_type');
    Route::get('/get_rule_speed/{id}', 'QuizRuleController@get_rule_speed');
    Route::resource('/feed-content', 'FeedContentController');
    Route::get('/feed-collection', 'FeedContentController@feed_collection_view');
    Route::resource('/tournament', 'TournamentController');
    Route::get('/tournament-excel-download', 'TournamentController@getDownloadExccelSheet')->name('tournament-excel-download');
    Route::get('/tournament_add', 'TournamentController@tournament_add')->name('tournament_add');
    Route::Post('/feed-collection-store','FeedContentController@feed_collection_store')->name('feed-collection-store');
    Route::Post('/tournament-questions-store','TournamentController@tournament_question_store')->name('tournament-questions-store');
    Route::get('/get-feed-content-by-id/{id}','FeedContentController@get_feed_content_by_id')->name('get_feed_content_by_id');
    Route::get('/update-feed-attchment','FeedContentController@update-feed-attchment')->name('update-feed-attchment');

});
// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/faqs', function(){
    $faqs=Faq::all();
    return view('faq',compact('faqs'));
});
