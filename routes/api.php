<?php

use Illuminate\Http\Request;

$api = app('Dingo\Api\Routing\Router');
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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

$api->version('v1', function ($api){

    $api->get('testcros', [
        'middleware' => 'cors',
        function () {
            return array('error_code'=>0,'error_msg'=>bcrypt('123456'));
        }
    ]);


	$api->group([
        'middleware' => [
            'cors'
        ],
        'namespace' => 'App\Api\V1\Controllers',
    ], function ($api){
        $api->post('user/signup','MemberController@signUp');
        $api->post('user/login','MemberController@login');
        $api->get('user/logout','MemberController@logout');
    });

    $api->group([
        'middleware' => [
            'cors',
            'api.auth'
        ],
        'namespace' => 'App\Api\V1\Controllers',
    ], function ($api){
        $api->get('user/userinfo', 'MemberController@userInfo');
        $api->post('user/profile', 'MemberController@updateUser');
    });
});



