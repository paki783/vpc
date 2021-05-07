<?php

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

Route::get('/resetPassword', "UserController@resetPassword");
Route::post('/UpdatePassword', "UserController@UpdatePassword");

route::get('logout', "UserController@logout");

route::prefix("admin")->group(function () {
    
    route::get('/', "UserController@admin");
    route::post('/user/auth', "UserController@admin_auth");

    Route::group([
        "middleware" => ["admin.auth"],
    ], function () {
        Route::group([
            "prefix" => "dashboard",
            // "middleware" => ["can:dashboard"],
        ], function () {
            route::get('/', "DashboardController@home");
        });
        
        Route::group([
            "prefix" => "settings",
            "middleware" => ["can:settings"],
        ], function () {
            route::get('/slider_all', "SettingsController@sliderAll");
            route::get('/slider/add_slide', "SettingsController@add_slide");
            route::get('/slider/edit', "SettingsController@edit");
            route::get('/slider/deleteSlide/', "SettingsController@deleteSlide");
            route::post('/slider/saveSlide', "SettingsController@saveSlide");

            route::get('/news_all', "SettingsController@newsAll");
            route::get('/news/add', "SettingsController@newsAdd");
            route::get('/news/edit', "SettingsController@newsEdit");
            route::get('/news/deleteNews', "SettingsController@deleteNews");
            route::post('/news/saveNews', "SettingsController@saveNews");

            route::get('/countries_all', "SettingsController@countries_all");
            route::get('/countries/edit', "SettingsController@countries_edit");
            route::post('/countries/saveCountry', "SettingsController@saveCountry");
        });

        Route::group([
            "prefix" => "teams",
            "middleware" => ["can:teams"],
        ], function () {
            route::get('/all', "TeamsController@all");
            route::get('/add_team', "TeamsController@add_team");
            route::get('/delete', "TeamsController@delete");
            route::get('/edit', "TeamsController@edit");
            route::post('/getManagerbyTeam', "TeamsController@getManagerbyTeam");
            route::post('/save_teams', "TeamsController@save_teams");

            route::get('/assign_teams', "TeamsController@assign_teams");
            route::get('/add_assign_team', "TeamsController@add_assign_team");
            route::get('/team_assign/delete', "TeamsController@delete_assign_team");
            route::get('/team_assign/edit', "TeamsController@edit_assign_team");
            route::post('/save_assign_team', "TeamsController@save_assign_team");
        });

        Route::group([
            "prefix" => "user",
            "middleware" => ["can:user"],
        ], function () {
            route::get('/all_user', "UserController@all_user");
            route::get('/detail', "UserController@details");
            route::get('/edit', "UserController@edit");
            Route::post('updateUser', 'UserController@updateUser');
            route::get('/promote', "UserController@promote");
            Route::post('search_user', 'UserController@search_user');
            route::get('/addUser', "UserController@addUser");
            route::post('/saveUser', "UserController@saveUser");
        });

        Route::group([
            "prefix" => "superusers",
            "middleware" => ["can:user"],
        ], function () {
            route::get('/all', "AdminController@alladmin");
            route::get('/add', "AdminController@add");
            route::get('edit', "AdminController@edit");

            Route::post('saveUser', "AdminController@saveUser");
            Route::post('updateUser', "AdminController@updateUser");
        });

        Route::group([
            "prefix" => "user/profile",
            "middleware" => ["can:user-profile-pending"],
        ], function () {
            route::get('/pending', "UserController@userProfilepending");
            route::get('/imageAction', "UserController@imageAction");
        });

        Route::group([
            "prefix" => "division",
            "middleware" => ["can:division"],
        ], function () {
            route::get('/all_division', "DivisionController@all_division");
            route::get('/delete', "DivisionController@delete");
            route::get('/add_division', "DivisionController@add_division");
            route::get('/edit', "DivisionController@edit");
            route::post('/saveDivision', "DivisionController@saveDivision");
            route::post('/getDivisionbyleague', "DivisionController@getDivisionbyleague");
        });

        Route::group([
            "prefix" => "games",
            "middleware" => ["can:games"],
        ], function () {
            Route::get('all_game', 'GamesController@all_game');
            Route::get('add_game', 'GamesController@add_game');
            Route::get('delete', 'GamesController@delete');
            Route::get('edit', 'GamesController@edit');
            Route::post('saveGame', 'GamesController@saveGame');
        });

        Route::group([
            "prefix" => "modes",
            "middleware" => ["can:modes"],
        ], function () {
            Route::get('all_mode', 'ModeController@all_mode');
            Route::get('add_mode', 'ModeController@add_mode');
            Route::get('delete', 'ModeController@delete');
            Route::get('edit', 'ModeController@edit');
            Route::post('saveMode', 'ModeController@saveMode');
        });

        Route::group([
            "prefix" => "role",
            "middleware" => ["can:role-management"],
        ], function () {
            Route::get('view', 'RoleController@view');
            Route::get('add', 'RoleController@add');
            Route::get('delete/{id}', 'RoleController@delete');
            Route::get('edit/{id}', 'RoleController@edit');
            Route::post('update', 'RoleController@update');
            Route::post('store', 'RoleController@store');
        });

        Route::group([
            "prefix" => "permission",
            "middleware" => ["can:role-management"],
        ], function () {
            Route::get('view', 'PermissionController@view');
            Route::get('add', 'PermissionController@add');
            Route::get('delete/{id}', 'PermissionController@delete');
            Route::get('edit/{id}', 'PermissionController@edit');
            Route::post('update', 'PermissionController@update');
            Route::post('store', 'PermissionController@store');
        });
        
        Route::group([
            "prefix" => "permission/assign",
            "middleware" => ["can:role-management"],
        ], function () {
            Route::get('view', 'PermissionAssignController@view');
            Route::get('edit/{id}', 'PermissionAssignController@edit');
            Route::post('update', 'PermissionAssignController@update');
        });

        Route::group([
            "prefix" => "plateforms",
            "middleware" => ["can:platforms"],
        ], function () {
            Route::get('all_plateforms', 'PlatformController@all_plateforms');
            Route::get('add_plateforms', 'PlatformController@add_plateforms');
            Route::get('delete', 'PlatformController@delete');
            Route::get('edit', 'PlatformController@edit');
            Route::post('saveplateforms', 'PlatformController@saveplateforms');
        });

        Route::group([
            "prefix" => "tournament",
            "middleware" => ["can:all-tournament"],
        ], function () {
            Route::get('all_tournament', 'TournamentController@all_tournament');
            Route::get('add_tournament', 'TournamentController@add_tournament');
            Route::get('edit', 'TournamentController@edit');
            Route::get('delete', 'TournamentController@delete');

            //set groups
            Route::get('setGroup', 'TournamentController@setgroup');
            Route::post('saveSetGroup', 'TournamentController@saveSetGroup');
            Route::get('genGroup', 'TournamentController@genGroup');
            Route::post('saveGenGroup', 'TournamentController@saveGenGroup');

            //set bracket
            route::get('setBracket', 'TournamentController@setBracket');
            route::post('createMatchBracket', 'TournamentController@createMatchBracket');
            route::get('getMatcheByBracket', 'TournamentController@getMatcheByBracket');

            route::get('bracketMatchStagView', 'TournamentController@bracketMatchStagView');

            route::get('bracket/match/edit', 'TournamentController@bracketMatchEdit');

            route::post('bracket/match/update', 'TournamentController@bracketMatchUpdate');

            route::get('bracket/match/delete', 'TournamentController@deleteMatcheByBracket');

            route::get('bracketMatchdelete', 'TournamentController@bracketMatchdelete');
            
            //tournament matches
            Route::get('all_tournament_matches', 'TournamentController@all_tournament_matches');

            Route::get('edit_group_tournament_matches', 'TournamentController@edit_group_tournament_matches');
            route::post('updateGroupTournamentScore', 'TournamentController@updateGroupTournamentScore');
            

            Route::post('saveTournament', 'TournamentController@saveTournament');

            Route::post('saveBracket', 'TournamentController@saveBracket');
        });

        Route::group([
            "prefix" => "league",
            "middleware" => ["can:league"],
        ], function () {
            Route::get('all_league', 'LeagueController@all_league');
            Route::get('add_league', 'LeagueController@add_league');
            Route::get('edit', 'LeagueController@edit');
            Route::get('delete', 'LeagueController@delete');
            Route::post('saveleague', 'LeagueController@saveTournament');
        });

        Route::group([
            "prefix" => "match",
            "middleware" => ["can:league"],
        ], function () {
            Route::get('all_match', 'MatchesController@all_match');
            Route::get('add_match', 'MatchesController@add_match');
            Route::get('edit', 'MatchesController@edit');
            Route::post('createMatch', 'MatchesController@createMatch');
            Route::post('updateScore', 'MatchesController@updateScore');
            Route::post('scoreProof', 'MatchesController@scoreProof');
            Route::get('delete', 'MatchesController@delete');
            Route::post('delete_all_match', 'MatchesController@delete_all_match');
        });

        Route::group([
            "prefix" => "vpc_system",
            "middleware" => ["can:vpc-system"],
        ], function () {
            Route::get('all', 'VPCSytemController@all');
            Route::get('add_vpc_system', 'VPCSytemController@add_vpc_system');
            Route::get('delete', 'VPCSytemController@delete');
            Route::get('edit', 'VPCSytemController@edit');
            Route::post('saveVpcSystem', 'VPCSytemController@saveVpcSystem');
        });

        Route::prefix('position')->group(function () {
            Route::get('all_position', 'PostionController@all_postion');
            Route::get('add_position', 'PostionController@add_position');
            Route::get('edit', 'PostionController@edit');
            Route::get('delete', 'PostionController@delete');
            Route::post('savePosition', 'PostionController@savePosition');
            Route::post('get_poistion', 'PostionController@get_poistion');
        });

        Route::group([
            "prefix" => "statistic",
            "middleware" => ["can:all-statistic"],
        ], function () {
            Route::get('all_statistic', 'StatisticController@all_statistic');
            Route::get('add_statistic', 'StatisticController@add_statistic');
            Route::get('edit', 'StatisticController@edit');
            Route::get('delete', 'StatisticController@delete');
            Route::post('saveStatistic', 'StatisticController@saveStatistic');
        });

        Route::group([
            "prefix" => "contract",
            "middleware" => ["can:contract"],
        ], function () {
            Route::get('all_contract', 'ContractController@all_contract');
            Route::get('edit', 'ContractController@edit');
            Route::get('add_contract', 'ContractController@add_contract');
            Route::get('delete', 'ContractController@delete');
            Route::post('uncontractuser', 'ContractController@uncontractuser');
            Route::post('saveContract', 'ContractController@saveContract');
            Route::post('getContractByTeams', 'ContractController@getContractByTeams');
            Route::post('updateContract', 'ContractController@updateContract');
        });

        Route::group([
            "prefix" => "awards",
            "middleware" => ["can:trophy"],
        ], function () {
            Route::get('all_awards', 'AwardController@all_awards');
            Route::get('add_awards', 'AwardController@add_awards');
            Route::get('delete', 'AwardController@delete');
            Route::get('edit', 'AwardController@edit');
            Route::get('all_assign', 'AwardController@all_assign');
            Route::get('add_assign_awards', 'AwardController@add_assign_awards');
            Route::get('/assign/delete', 'AwardController@delete_assign_awards');
            Route::get('/assign/edit', 'AwardController@edit_assign_awards');
            Route::post('saveAward', 'AwardController@saveAward');
            Route::post('save_AssignAward', 'AwardController@save_AssignAward');
        });

        Route::group([
            "prefix" => "medals",
            "middleware" => ["can:medals"],
        ], function () {
            Route::get('all_medals', 'MedalController@all_medals');
            Route::get('add_medals', 'MedalController@add_medals');
            Route::get('delete', 'MedalController@delete');
            Route::get('edit', 'MedalController@edit');
            Route::get('all_assign_medal', 'MedalController@all_assign_medal');
            Route::get('assign/add_assign_medals', 'MedalController@add_assign_medals');
            Route::get('edit_assign_medals', 'MedalController@edit_assign_medals');
            Route::get('delete_assign_medals', 'MedalController@delete_assign_medals');
            Route::post('saveMedal', 'MedalController@saveMedal');
            Route::post('saveAssignMedal', 'MedalController@saveAssignMedal');
        });

        Route::group([
            "prefix" => "leaderboard",
            "middleware" => ["can:leaderboard"],
        ], function () {
            Route::get('all_leaderboard', 'LeaderboardController@all_leaderboard');
            Route::get('add_leaderboard', 'LeaderboardController@add_leaderboard');
            Route::get('delete', 'LeaderboardController@delete');
            Route::get('edit', 'LeaderboardController@edit');
            Route::post('saveLeaderboard', 'LeaderboardController@saveLeaderboard');
        });

        Route::prefix('ajax')->group(function () {
            Route::get('searchMode', 'AjaxController@searchMode');
        });
    });
});
