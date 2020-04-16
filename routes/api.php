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

Route::get('users/photo/{email}', 'UserControllerAPI@getPhotoByEmail')->middleware('auth:api'); //->middleware('isUser')
Route::get('users/{email}', 'UserControllerAPI@checkEmailExists');
Route::delete('users/{id}', 'UserControllerAPI@remove')->name('removeUser')->middleware('auth:api'); //->middleware('isAdmin')
Route::post('users', 'UserControllerAPI@store')->name('storeUser')->middleware('auth:api'); //->middleware('isAdmin')
Route::put('users/update/{id_user}', 'UserControllerAPI@updateProfile')->name('updateProfile')->middleware('auth:api'); //->middleware('isAuthUser')

Route::get('contactos', 'ContactoControllerAPI@getContactos')->middleware('auth:api'); //->middleware('isAdmin')
Route::post('contactos', 'ContactoControllerAPI@store')->middleware('auth:api'); //->middleware('isAdmin')
Route::put('contactos/{contacto_id}/update', 'ContactoControllerAPI@update')->name('updateContacto')->middleware('auth:api'); //->middleware('isAdmin')

Route::post('login', 'LoginControllerAPI@login')->name('login');
Route::post('logout','LoginControllerAPI@logout')->name('logout')->middleware('auth:api');
