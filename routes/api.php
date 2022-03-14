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

Route::post('/forgetPassword', 'UserController@forgetPassword');
Route::post('updatetoken', 'UserController@updatetoken');

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
Route::post('dashboard', 'HomeController@dashboard');
Route::post('link_details', 'HomeController@link_details');

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
Route::get('save_feed', 'FeedContentController@save_feed');
// Filter saved feeds
Route::post('filter_feed', 'FeedContentController@filter_feed');

/**  End from Here        */

/**  Product APi Routes  Start from Here      */
Route::get('get_all_products', 'ProductController@get_all_products');
Route::get('product_search', 'ProductController@product_search');
/**  End from Here        */


/**  Product APi Routes  Duel Apis from Here      */
Route::post('create_duel', 'DuelController@create_duel');
Route::post('get_all_users', 'DuelController@get_all_users');
Route::post('send_invitation', 'DuelController@send_invitation');
Route::post('accept_invitation', 'DuelController@accept_invitation');
Route::post('generate_link', 'DuelController@generate_link');
Route::get('dual/{id}', 'DuelController@dual');
Route::post('get_dual_result', 'DuelController@get_dual_result');
Route::post('dual_status', 'DuelController@dual_status');
Route::post('duel_rules', 'DuelController@quiz_rules');
Route::post('reject_invitation', 'DuelController@reject_invitation');
Route::post('dualdetails', 'DuelController@dualdetails');

// Route::post('savedual', 'DuelController@submit_exam');
// Route::get('fetch_dual_question/{id}', 'DuelController@fetch_dual_question');


/**  End from Here        */




/**  Tournament APi Routes  Start from Here      */
Route::get('tournament', 'TournamentController@tournament');

Route::post('tournament_rule', 'TournamentController@tournament_rule');
Route::post('join_tournament', 'TournamentController@join_tournament');
Route::post('tournament_questions', 'TournamentQuestionController@tournament_questions');
Route::post('tournament_result', 'TournamenetUserController@tournament_result');
Route::post('get_tournament_rank', 'TournamenetUserController@get_tournament_rank');
Route::post('get_tournament_answer', 'TournamenetUserController@get_tournament_answer');
Route::get('userleague', 'TournamenetUserController@userleague');
Route::get('leaguerank', 'TournamenetUserController@leaguerank');
Route::get('xprewards', 'TournamenetUserController@xprewards');

/**  End from Here        */

// user report for a quiz 
Route::post('report', 'UserReportController@userreport');

// Dispute if any
Route::post('raise_dispute', 'DisputeController@store');


/*

/** add help  */
Route::post('add_help', 'HelpAndSupportController@add_help');


/*

These are the auth routes
End Here
*/

// For server date and time
Route::get('currentDateTime', 'UserController@currentDateTime');


/* Account Page API 
      Start Here
      */
//========= Notification API
Route::get('notification/{id}', 'NotificationController@fetchNotification');
Route::post('notification', 'NotificationController@save');
// ========= Contact API
Route::post('import_contact', 'ContactController@import_contact');
Route::post('get_all_contacts', 'ContactController@fetchContacts');
Route::post('get_block_user', 'ContactController@blockUser');
Route::post('blockuser', 'ContactController@blockAUser');
Route::post('deleteuser', 'ContactController@deleteUser');
Route::post('invite_contact', 'ContactController@invite_contact');
Route::post('accept_link_invitation', 'ContactController@accept_link_invitation');
Route::post('add_friend', 'ContactController@add_friend');
Route::post('unblockuser', 'ContactController@unblockUser');
Route::post('check_friend', 'ContactController@check_friend');
Route::post('reject_link_invitation', 'ContactController@reject_link_invitation');
//========= Privacy API
Route::get('privacy/{id}', 'PrivacyController@fetchPrivacy');
Route::post('privacy', 'PrivacyController@save');

/* Account Page API 
      ENDS Here
      */

//========= Check user status free or busy
Route::post('busy', 'UserController@busyUser');
Route::post('free', 'UserController@freeUser');

//========= Quiz ROOM API ========

Route::post('create_quiz_room', 'QuizRoomController@create_quiz_room');
Route::post('quiz_room_rule', 'QuizRoomController@quiz_rules');
Route::post('disband_quiz', 'QuizRoomController@disband_quiz');
Route::post('send_invitation_quiz_room', 'QuizRoomController@send_invitation');
Route::post('accept_invitation_quiz_room', 'QuizRoomController@accept_invitation');
Route::post('generate_link_quiz_room', 'QuizRoomController@generate_link');
Route::post('room_user', 'QuizRoomController@room_user');
Route::post('delete_user_room', 'QuizRoomController@delete_user_room');
Route::post('leaveroom', 'QuizRoomController@leaveroom');
Route::post('save_room_result', 'QuizRoomController@save_room_result');
Route::post('reject_room_invitation', 'QuizRoomController@reject_invitation');
Route::post('get_room_result', 'QuizRoomController@get_room_result');

Route::post('checkquiz', 'HomeController@checkquiz');


// Chart and User data api
Route::post('xpgainchart', 'ProfileController@xpgainchart');
Route::post('badges', 'BadgeController@userbadges');
Route::post('checkbadge', 'BadgeController@checkbadge');
Route::post('goals', 'GoalController@setgoal');
Route::post('goalsummary', 'GoalController@goalsummary');
Route::post('leaderboardranking', 'TournamenetUserController@leaderboardranking');
Route::post('user_profile', 'ProfileController@user_profile'); //  user and contact details on profile
