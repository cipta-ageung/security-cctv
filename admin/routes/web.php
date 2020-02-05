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


Route::get('/', 'Auth\LoginController@showLoginForm');

Auth::routes();

// Dashboard
Route::get('/home', 'HomeController@index')->name('home');

// News Update
Route::get('/news', 'NewsController@index')->name('news.index');
Route::get('/news/create', 'NewsController@create')->name('news.create');
Route::post('/news/store', 'NewsController@store')->name('news.store');
Route::get('news/{id}/edit/', 'NewsController@edit')->name('news.edit');
Route::post('news/update', 'NewsController@update')->name('news.update');
Route::post('news/delete/{id}', 'NewsController@delete')->name('news.delete');

// Product
Route::get('/product', 'ProductController@index')->name('product.index');
Route::get('/product/create', 'ProductController@create')->name('product.create');
Route::post('/product/store', 'ProductController@store')->name('product.store');
Route::get('/product/{id}/edit', 'ProductController@edit')->name('product.edit');
Route::post('/product/update', 'ProductController@update')->name('product.update');

// Facilities
Route::get('/facilities/{id}/list', 'FacilitiesController@index')->name('facilities.index');
Route::get('/facilities/{id}/create', 'FacilitiesController@create')->name('facilities.create');
Route::post('/facilities/store', 'FacilitiesController@store')->name('facilities.store');
Route::post('/facilities/delete/{id}', 'FacilitiesController@delete')->name('facilities.delete');

// House Type
Route::get('/house-type/{id}/list', 'HouseTypeController@index')->name('houseType.index');
Route::get('/house-type/{id}/create', 'HouseTypeController@create')->name('houseType.create');
Route::post('/house-type/store', 'HouseTypeController@store')->name('houseType.store');
Route::post('/house-type/delete/{id}', 'HouseTypeController@delete')->name('houseType.delete');

// Administrator
Route::get('/administrator', 'AdminController@index')->name('admin.index');
Route::get('/administrator/create', 'AdminController@create')->name('admin.create');
Route::post('/administrator/store', 'AdminController@store')->name('admin.store');
Route::get('/administrator/{id}/edit', 'AdminController@edit')->name('admin.edit');
Route::post('/administrator/update', 'AdminController@update')->name('admin.update');
Route::post('/administrator/delete/{id}', 'AdminController@delete')->name('admin.delete');

// Penghuni
Route::get('/occupant', 'OccupantController@index')->name('occupant.index');
Route::get('/occupant/create', 'OccupantController@create')->name('occupant.create');
Route::post('/occupant/store', 'OccupantController@store')->name('occupant.store');
Route::get('/occupant/{id}/edit', 'OccupantController@edit')->name('occupant.edit');
Route::post('/occupant/update', 'OccupantController@update')->name('occupant.update');
Route::post('/occupant/reset/{id}', 'OccupantController@reset')->name('occupant.reset');
Route::post('/occupant/delete/{id}', 'OccupantController@delete')->name('occupant.delete');

// CCTV
Route::get('cctv', 'CctvController@index')->name('cctv.index');
Route::get('cctv/create', 'CctvController@create')->name('cctv.create');
Route::post('cctv/store', 'CctvController@store')->name('cctv.store');
Route::get('cctv/{id}/edit', 'CctvController@edit')->name('cctv.edit');
Route::post('cctv/update', 'CctvController@update')->name('cctv.update');
Route::post('cctv/delete/{id}', 'CctvController@delete')->name('cctv.delete');

// Contact
Route::get('contact', 'ContactController@index')->name('contact.index');
Route::get('contact/create', 'ContactController@create')->name('contact.create');
Route::post('contact/store', 'ContactController@store')->name('contact.store');
Route::get('contact/{id}/edit', 'ContactController@edit')->name('contact.edit');
Route::post('contact/update', 'ContactController@update')->name('contact.update');
Route::post('contact/delete/{id}', 'ContactController@delete')->name('contact.delete');

// Contact
Route::get('about-apps', 'AboutAppsController@index')->name('about.index');
Route::get('about-apps/create', 'AboutAppsController@create')->name('about.create');
Route::post('about-apps/store', 'AboutAppsController@store')->name('about.store');
Route::get('about-apps/{id}/edit', 'AboutAppsController@edit')->name('about.edit');
Route::post('about-apps/update', 'AboutAppsController@update')->name('about.update');
Route::post('about-apps/{id}/delete', 'AboutAppsController@delete')->name('about.delete');

// Security
Route::get('security', 'SecurityController@index')->name('security.index');
Route::get('security/create', 'SecurityController@create')->name('security.create');
Route::post('security/store', 'SecurityController@store')->name('security.store');
Route::get('security/{id}/edit', 'SecurityController@edit')->name('security.edit');
Route::post('security/update', 'SecurityController@update')->name('security.update');
Route::post('security/delete/{id}', 'SecurityController@delete')->name('security.delete');
Route::post('security/reset/{id}', 'SecurityController@reset')->name('security.reset');

Route::get('security/{id}/schedule', 'SecurityController@scheduleIndex')->name('securitySchedule.index');
Route::get('security/{id}/schedule/create', 'SecurityController@scheduleCreate')->name('securitySchedule.create');
Route::post('security/schedule/store', 'SecurityController@scheduleStore')->name('securitySchedule.store');
Route::get('security/{id}/schedule/edit', 'SecurityController@scheduleEdit')->name('securitySchedule.edit');
Route::post('security/schedule/update', 'SecurityController@scheduleUpdate')->name('securitySchedule.update');
Route::post('security/schedule/delete/{id}', 'SecurityController@scheduleDelete')->name('securitySchedule.delete');

// Payment Reminder
Route::get('reminder-installment', 'PaymentInstallmentController@index')->name('reminder-installment.index');
Route::get('reminder-installment/create', 'PaymentInstallmentController@create')->name('reminder-installment.create');
Route::post('reminder-installment/store', 'PaymentInstallmentController@store')->name('reminder-installment.store');
Route::get('reminder-installment/{id}/edit', 'PaymentInstallmentController@edit')->name('reminder-installment.edit');
Route::post('reminder-installment/update', 'PaymentInstallmentController@update')->name('reminder-installment.update');

Route::get('reminder-ipl', 'PaymentIplController@index')->name('reminder-ipl.index');
Route::get('reminder-ipl/create', 'PaymentIplController@create')->name('reminder-ipl.create');
Route::post('reminder-ipl/store', 'PaymentIplController@store')->name('reminder-ipl.store');
Route::get('reminder-ipl/{id}/edit', 'PaymentIplController@edit')->name('reminder-ipl.edit');
Route::post('reminder-ipl/update', 'PaymentIplController@update')->name('reminder-ipl.update');

// Payment Jualan
Route::get('jualan', 'JualanController@index')->name('jualan.index');
Route::post('jualan/{id}', 'JualanController@delete')->name('jualan.delete');

// Logs
Route::get('admin-logs', 'LogsController@adminLogs')->name('logs.admin'); 
Route::get('apps-logs', 'LogsController@appsLogs')->name('logs.apps');
Route::get('apps-panic', 'LogsController@panicLogs')->name('logs.panic');

Route::get('profil', 'ProfileController@index')->name('profil.index');
Route::post('profil/update', 'ProfileController@update')->name('profil.update');

Route::post('ganti-password', 'ProfileController@changePassword')->name('gantiPassword');