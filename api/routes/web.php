<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return response()->json(['code' => 200, 'description' => 'API AQUILA LAND PROPERTY', 'version' => '1.0']);
});



$router->group(['prefix' => 'v1'], function () use ($router){
    $router->group(['prefix'=>'auth'],function () use ($router){
        $router->post('login','AuthController@login');
    });
    
    $router->post('fcm', 'ProfileController@fcm');
    
    $router->group(['prefix' => 'product'], function () use ($router){
        $router->get('list','ProductController@list');
        $router->get('detail/{id}','ProductController@detail');
        $router->get('aboutApps','ProductController@aboutApps');
    });
    
    $router->group(['prefix' => 'berita'], function() use($router){
        $router->get('list', 'BeritaController@index');
        $router->get('detail/{id}', 'BeritaController@detail');
    });
    
    $router->group(['prefix' => 'profile', 'middleware' => ['jwt.auth']], function() use($router){
        $router->get('', 'ProfileController@profile');
        $router->post('update', 'ProfileController@update');
        $router->post('gantiPassword', 'ProfileController@gantiPassword');
        $router->post('logout','AuthController@logout');
    });

    $router->group(['prefix' => 'fasum', 'middleware' => ['jwt.auth']], function() use($router){
        $router->get('', 'FasumController@index');
    });

    $router->group(['prefix' => 'security', 'middleware' => ['jwt.auth']], function() use($router){
        $router->get('', 'SecurityController@index');
    });

    $router->group(['prefix' => 'cctv', 'middleware' => ['jwt.auth']], function() use($router){
        $router->get('', 'CctvController@index');
    });

    $router->group(['prefix' => 'inbox', 'middleware' => ['jwt.auth']], function() use($router){
        $router->get('', 'InboxController@inbox');
        $router->get('ipl' , 'InboxController@ipl');
        $router->get('cicilan' , 'InboxController@cicilan');
    });

    $router->group(['prefix' => 'panic', 'middleware'=> ['jwt.auth']], function() use($router){
        $router->post('', 'PanicController@pushNotif');
        $router->post('popUp', 'PanicController@pushNotifPopUp');
    });

    $router->group(['prefix' => 'jualan', 'middleware' => ['jwt.auth']], function() use($router){
        $router->get('', 'JualanController@index');
        $router->get('detail/{id}', 'JualanController@detail');
        $router->get('produk-saya', 'JualanController@produkSaya');
        $router->post('posting', 'JualanController@produkPosting');
        $router->post('update', 'JualanController@update');
        $router->post('delete', 'JualanController@delete');
    });

    $router->group(['prefix' => 'testing'], function() use($router){
        $router->post('berita', 'TestingController@berita');
        $router->post('beritaTopics', 'TestingController@beritaTopics');
        $router->post('gantiPwd', 'TestingController@resetPwd');
        $router->get('checkInbox', 'TestingController@cekInbox');
    });
    
});