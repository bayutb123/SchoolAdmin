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

Route::get('/profile', 'ProfileController@index')->name('profile');
Route::put('/profile', 'ProfileController@update')->name('profile.update');

Route::get('/issues', 'IssueController@index')->name('issue');
Route::get('/issues/add', 'IssueController@create')->name('issue.create');

Route::get('/requests', 'RequestController@index')->name('request');
Route::get('/requests/add', 'RequestController@create')->name('request.create');

Route::get('/inventory', 'InventoryController@index')->name('inventory');
Route::get('/inventory/add', 'InventoryController@create')->name('inventory.create');
Route::post('/inventory/add', 'InventoryController@store')->name('inventory.store');

Route::get('/about', function () {
    return view('about');
})->name('about');
