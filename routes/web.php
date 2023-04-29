<?php
use App\User;

use Illuminate\Support\Facades\Route;
use App\Faq;
use App\Http\Controllers\SocialMediaController;
use Illuminate\Support\Facades\Auth;

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
   // auth()->login(User::first());
   // auth()->login(User::where('id','2')->first());

    return view('welcome');
});

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:cache');
    // return what you want
});
Auth::routes();
Route::get('/change_password/{id}','UserController@change_passwords');
Route::post('/password_update','UserController@password_update');
Route::view('/success','auth.passwords.success');

Route::get('/home', 'HomeController@index')->name('home');
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::resource('/domain', 'DomainController');
    Route::get('/users', 'UserController@index');

   // Sub Domain  routes
    Route::post('/sub-domain-status', 'DomainController@addsubdomain')->name('addsubdomain');
    Route::get('/sub-domain-status/{id}', 'DomainController@changeSubDomainStatus')->name('changesubdomain');
    Route::put('/subdomain/{id}', 'DomainController@updatesubdomain')->name('subdomain');
    Route::delete('/subdomain/{id}', 'DomainController@deletesubdomain')->name('subdomain');
    // Age Group Route::
    Route::resource('/agegroup', 'AgeGroupController');
    // DIfficulty level routes
    Route::resource('/difflevel', 'DifficultyLevelController');
    // QuizSpeed ROutes
    Route::resource('/quizspeed', 'QuizSpeedController');
    // Quiz type routes
    Route::resource('/quiztype', 'QuizTypeController');
    //Question routes
    Route::resource('/question', 'QuestionController');
    // Product routes
    Route::resource('/product', 'ProductController');
    // Expreince routes
    Route::resource('/experince', 'ExperinceController');

    // Question builk import routes
    Route::view('/form_bulk','question.UploadBulk');
    Route::post('/upload_bulk','QuestionController@import')->name('upload_bulk');
    // Faq routes
    Route::resource('/faq', 'FaqController');
    //Quiz rule routes
    Route::resource('/quizrules', 'QuizRuleController');
    // Quiz rule fetch route
    Route::get('/get_rule_type/{id}', 'QuizRuleController@get_rule_type');

    Route::get('/get_rule_speed/{id}', 'QuizRuleController@get_rule_speed');
    // Feed routes
    Route::resource('/feed-content', 'FeedContentController');
    Route::get('/feed-collection', 'FeedContentController@feed_collection_view');
    // Tournament ROutes
    Route::resource('/tournament', 'TournamentController');
    Route::get('/tournament-excel-download', 'TournamentController@getDownloadExccelSheet')->name('tournament-excel-download');
    Route::get('/tournament_add', 'TournamentController@tournament_add')->name('tournament_add');
    Route::Post('/feed-collection-store','FeedContentController@feed_collection_store')->name('feed-collection-store');
    Route::Post('/tournament-questions-store','TournamentController@tournament_question_store')->name('tournament-questions-store');
    Route::get('/get-feed-content-by-id/{id}','FeedContentController@get_feed_content_by_id')->name('get_feed_content_by_id');
    Route::get('/quesbyid/{id}', 'QuestionController@quesbyid')->name('quesbyid');
       // Feed attchment routes 
    Route::Post('/update-feed-attchment','FeedContentController@update_feed_attachment')->name('update-feed-attchment');
     // Feed Media and Feed routes 
    Route::get('/edit_media','FeedContentController@edit_media')->name('edit_media');
    Route::post('/update_feed_media','FeedContentController@update_feed_media')->name('update_feed_media');
    Route::get('/add_media','FeedContentController@add_media')->name('add_media');
    Route::post('/add_feed_media','FeedContentController@add_feed_media')->name('add_feed_media');
   // Tournament rules
    Route::resource('/tourrule', 'TournamentRuleController');
    Route::get('addrule', 'TournamentRuleController@addrule')->name('addrule');
    Route::get('help', 'HelpAndSupportController@index')->name('help');
    Route::get('disputes', 'DisputeController@index')->name('disputes');
    // Domain routes
    Route::post('select-domain', 'DomainController@selectdomain')->name('select-domain');
    Route::post('select-subdomain', 'DomainController@selectsubdomain')->name('select-subdomain');

});
// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/faqs', function(){
    $faqs=Faq::where('status','1')->get();
    return view('faq',compact('faqs'));
});
Route::get('/cul.tre/{id}', 'HomeController@download');
Route::post('fbdeleterequest', 'SocialMediaController@fbdeleterequest');
Route::get('fbdeletioncheck', 'SocialMediaController@fbdeletioncheck');
Route::get('test', 'SocialMediaController@test');
Route::get('privacy-policy','SocialMediaController@privacypolicy');
Route::get('terms-amp-conditions', 'SocialMediaController@terms');
Route::get('twittercall', 'SocialMediaController@twittercallback');
// http://www.cultre.com/cul.tre/duel#80
