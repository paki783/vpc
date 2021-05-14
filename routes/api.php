<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API Routes for your application. These
| Routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('clear-compiled');
    // config:clear
    // return what you want
});

Route::prefix("user")->group(function () {
    
    //Route::post('signup', [UserController::class, 'create');
    Route::post('register', 'UserController@register');
    Route::post('login', 'UserController@login');
    ///Route::post('login', 'UserController@login');
    Route::post('forgetPassword', 'UserController@forgetPassword');

    Route::group([
            'middleware' => ['jwt.auth:api'],
        ], function () {
            //Admin Login
            Route::get('profile', 'UserController@profile');

            Route::post('userHome', 'UserController@userHome');
            Route::post('getManagerDetails', 'UserController@getManagerDetails');
            Route::post('api_updateprofile', 'UserController@api_updateprofile');
            //
        });
});

Route::prefix("tournament")->group(function () {
    Route::group([
        'middleware' => ['jwt.auth:api'],
    ], function () {
        Route::post('all_tournament', 'TournamentController@all_tournament');
        Route::post('getTournamentTeams', 'TournamentController@getTournamentTeams');
        Route::post('getMatcheByBracket', 'TournamentController@getMatcheByBracket');
    });
});

Route::prefix("games")->group(function () {
    Route::group([
        'middleware' => ['jwt.auth:api'],
    ], function () {
        Route::post('getGamesAPI', 'GamesController@getGamesAPI');
        Route::post('gamesIPlayed', 'GamesController@gamesIPlayed');
    });
});

Route::prefix("league")->group(function () {
    Route::group([
        'middleware' => ['jwt.auth:api'],
    ], function () {
        Route::post('all_league', 'LeagueController@all_league');
        Route::post('api_leagueall', 'LeagueController@api_leagueall');
    });
});
Route::post('api_leagueall', 'LeagueController@api_leagueall');
Route::prefix("teams")->group(function () {
    Route::group([
        'middleware' => ['jwt.auth:api'],
    ], function () {
        Route::post('/team_assign/edit', "TeamsController@edit_assign_team");
        Route::post('/get_matchesBy_manager', "TeamsController@get_matchesBy_manager");
        Route::post('/get_player_team', "TeamsController@get_player_team");
        Route::post('/getPlayersByTeam', "TeamsController@getPlayersByTeam");
    });
});
Route::prefix("match")->group(function () {
    Route::group([
        'middleware' => ['jwt.auth:api'],
    ], function () {
        Route::post('all_match', 'MatchesController@all_match');
        Route::post('get_user_match', 'MatchesController@get_user_match');
        Route::post('uploadScore', 'MatchesController@uploadScore');
        Route::post('change_match_status', 'MatchesController@change_match_status');
        Route::post('get_matches_by_team', 'MatchesController@get_matches_by_team');
    });
});
Route::prefix("awards")->group(function () {
    Route::group([
        'middleware' => ['jwt.auth:api'],
    ], function () {
        Route::post('getAwardbyteam', 'AwardController@getAwardbyteam');
        Route::post('getAwardbyleague', 'AwardController@getAwardbyleague');
    });
});
Route::prefix("user")->group(function () {
    Route::group([
        'middleware' => ['jwt.auth:api'],
    ], function () {
        Route::post('search_user', 'UserController@search_user');
        Route::post('SaveAssistance', 'UserController@SaveAssistance');
        Route::post('getplayerdetails', 'UserController@getplayerdetails');
    });
});

Route::prefix("contract")->group(function () {
    Route::group([
        'middleware' => ['jwt.auth:api'],
    ], function () {
        Route::post('saveContract', 'ContractController@saveContract');
        Route::post('Offeraction', 'ContractController@Offeraction');
        Route::post('viewContract', 'ContractController@viewContract');
        Route::post('getMyContract', 'ContractController@getMyContract');
        Route::post('getManagerContract', 'ContractController@getManagerContract');
        Route::post('getContractByTeams', 'ContractController@getContractByTeams');
    });
});

Route::prefix("position")->group(function () {
    Route::group([
        'middleware' => ['jwt.auth:api'],
    ], function () {
        Route::post('get_poistion', 'PlayerPositionController@get_poistion');
        Route::post('savePlayerPosition', 'PlayerPositionController@savePlayerPosition');
    });
});
Route::prefix("mode")->group(function () {
    Route::group([
        'middleware' => ['jwt.auth:api'],
    ], function () {
        Route::post('get_league_mode', 'ModeController@get_league_mode');
        Route::post('all_mode', 'ModeController@all_mode');
    });
});
Route::prefix("medals")->group(function () {
    Route::group([
        'middleware' => ['jwt.auth:api'],
    ], function () {
        Route::post('getmedalbyuser', 'MedalController@getmedalbyuser');
        Route::post('getmedalbyteam', 'MedalController@getmedalbyteam');
    });
});

Route::prefix("leaderboard")->group(function () {
    Route::group([
        'middleware' => ['jwt.auth:api'],
    ], function () {
        Route::post('getLeaderboardbyuser', 'LeaderboardController@getLeaderboardbyuser');
        Route::post('getLeaderboardbyteam', 'LeaderboardController@getLeaderboardbyteam');
        Route::post('getLeaderboards', 'LeaderboardController@getLeaderboards');
    });
});

Route::prefix("favourite")->group(function () {
    Route::group([
        'middleware' => ['jwt.auth:api'],
    ], function () {
        Route::post('save', 'FavouriteController@save');
        Route::post('get', 'FavouriteController@get');
        Route::post('remove', 'FavouriteController@remove');
    });
});

Route::prefix("settings")->group(function () {
    Route::group([
        'middleware' => ['jwt.auth:api'],
    ], function () {
        Route::post('api_getcountry', 'SettingsController@api_getcountry');
    });
});


Route::group([
    'prefix' => "v1",
    'namespace' => 'Api\V1',
    'middleware' => ['jwt.auth:api'],
], function () {
    Route::post('contact', 'ContactController@ContactForm');

    Route::post('profile/update', 'FeatureController@updateProfile');
    Route::post('package/list', 'FeatureController@packageList');
    Route::get('rule', 'FeatureController@ruleGet');
    Route::post('get/team', 'FeatureController@teamGet');
    Route::post('get/teamAndAssistant', 'FeatureController@teamAndAssistantGet');

    Route::post('get/user', 'FeatureController@userGet');
    Route::get('contract/list', 'FeatureController@contractList');
    Route::get('match/list', 'FeatureController@matchList');

    Route::get('game/list', 'FeatureController@gameList');
    
    Route::get('get/team/noContact', 'FeatureController@getTeamNoContact');

    Route::get('get/user/noContact', 'FeatureController@getUserNoContact');

    Route::get('get/all/team-by/league/division/list', 'FeatureController@getAllTeamByLeagueAndDivisionList');
    
    Route::post('leaderboard/getLeaderboardByLeague', 'LeaderboardController@getLeaderboardByLeague');
    Route::post('leaderboard/getLeaderboard', 'LeaderboardController@getLeaderboard');

    Route::post('make/assistant', 'AssistantController@makeAssistant');
    Route::post('remove/assistant', 'AssistantController@removeAssistant');
    Route::post('get/assistant/manager', 'AssistantController@getAssistantManager');

    // Tournament
    Route::post('get/tournament/bracket/list', 'TournamentController@getBracketTournament');


    // Device CRUD
    Route::post('device/create', 'DeviceController@create');
    Route::post('device/update', 'DeviceController@update');
    Route::post('device/delete', 'DeviceController@delete');
    Route::get('device/list', 'DeviceController@list');
    Route::post('device/token/update', 'DeviceController@deviceTokenUpdate');

    // Logout
    Route::post('user/logout', 'FeatureController@logout');

    // notification
    Route::get('notification/list', 'NotificationController@list');
    Route::get('get/notification/unread', 'NotificationController@unreadCount');
    
    // line_up
    Route::get('line_up/get/contract/user', 'LineUpController@getContractUser');
    Route::post('line_up/player/submit', 'LineUpController@playerSubmit');
    
    // match
    Route::post('match/win/byManager', 'MatchController@winByManager');
    Route::get('get/team/match/', 'MatchController@getTeamMatch');
    
    Route::post('check/match/result', 'MatchController@checkMatchResult');
    
    // statistic
    Route::post('get/game/statistic', 'StatisticController@getGameStatistic');
    Route::post('submit/statistic', 'StatisticController@submitStatistic');
    Route::get('get/user/statistic/position', 'StatisticController@getUserStatisticPosition');
    
    
});
