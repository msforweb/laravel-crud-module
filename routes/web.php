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


# Client module route
Route::resource('/clients', 'Agents\ClientController');
Route::post('/clients/isActive', 'Agents\ClientController@isActive')->name('clients.isActive');

Route::resource('/marketing', 'Agents\CampaignController');
Route::resource('/deals', 'Agents\DealsController');

Route::get('logout', 'Auth\LoginController@logout');
Auth::routes();

# Properties Listing
Route::post('/ajax', 'Agents\AjaxRequestController@AjaxRequestData');
Route::post('/suggestion', 'Agents\AutoSuggestionController@getAutoSuggestions');

Route::get('/', 'HomeController@index')->name('home');
Route::get('/style-front', 'StyleController@index');
Route::get('/js-front', 'JsController@index');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::get('properties/{query?}','Agents\PropertiesListingController@getPropertiesList')->where('query', '.*');
Route::get('save-search.html','Agents\PopupController@main')->defaults('param', 'savesearch');
Route::get('email-to-friend.html','Agents\PopupController@main')->defaults('param', 'email-to-friend');
Route::get('stripe-order-sign.html','Agents\PopupController@main')->defaults('param', 'stripe-order-sign');







