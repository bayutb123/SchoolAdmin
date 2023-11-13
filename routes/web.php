<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

// profile
Route::get('/profile', 'ProfileController@index')->name('profile');
Route::put('/profile', 'ProfileController@update')->name('profile.update');

// inventory
Route::get('/inventory', 'InventoryController@index')->name('inventory');
Route::get('/inventory/add', 'InventoryController@create')->name('inventory.create');
Route::post('/inventory/add', 'InventoryController@store')->name('inventory.store');
Route::get('/inventory/{id}/edit', 'InventoryController@edit')->name('inventory.edit');
Route::put('/inventory/edit', 'InventoryController@update')->name('inventory.update');
Route::get('/inventory/request/add', 'InventoryController@request')->name('inventory.request');
Route::post('/inventory/request/add', 'InventoryController@requestStore')->name('inventory.request.store');
Route::get('/inventory/request/{id}/edit', 'InventoryController@requestEdit')->name('inventory.request.edit');
Route::put('/inventory/request/edit', 'InventoryController@requestUpdate')->name('inventory.request.update');

// room
Route::get('/room', 'RoomController@index')->name('room');

Route::get('/room/add', 'RoomController@create')->name('room.create');
Route::post('/room/add', 'RoomController@store')->name('room.store');
Route::get('/room/{id}/edit', 'RoomController@edit')->name('room.edit');
Route::put('/room/edit', 'RoomController@update')->name('room.update');

// issue
Route::get('/issue', 'IssueController@index')->name('issue');
Route::get('/issue/add', 'IssueController@create')->name('issue.create');
Route::post('/issue/add', 'IssueController@store')->name('issue.store');
Route::get('/issue/{id}/edit', 'IssueController@edit')->name('issue.edit');
Route::put('/issue/edit', 'IssueController@update')->name('issue.update');
Route::get('/issue/{id}/print', 'IssueController@print')->name('issue.print');

// approve issue
Route::get('/issue/{id}/detail', 'IssueController@detail')->name('issue.detail');
Route::put('/issue/detail', 'IssueController@approve')->name('issue.approve');

// request
Route::get('/request', 'RequestController@index')->name('request');
Route::get('/request/add', 'RequestController@create')->name('request.create');
Route::post('/request/add', 'RequestController@store')->name('request.store');
Route::get('/request/{id}/edit', 'RequestController@edit')->name('request.edit');
Route::put('/request/edit', 'RequestController@update')->name('request.update');
Route::get('/request/{id}/print', 'RequestController@print')->name('request.print');

// approve request
Route::get('/request/{id}/detail', 'RequestController@detail')->name('request.detail');
Route::put('/request/detail', 'RequestController@approve')->name('request.approve');


Route::get('/about', function () {
    return view('about');
})->name('about');
