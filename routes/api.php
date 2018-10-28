<?php

use Illuminate\Http\Request;

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

//авторизация, выход
Route::post('register', 'Auth\RegisterController@register');// было использовано для создания записи администратора
Route::post('auth', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

//просмотр записей, просмотр записи, поиск записи по тегу
//GET
Route::get('/posts', 'PostsController@index');
Route::get('/posts/{post}', 'PostsController@show');
Route::get('/posts/tag/{tags}', 'PostsController@search');

//Создание, редактирование, удаление статей, удаление коментариев
//только администратору, с bearer токеном
Route::middleware('auth:api')->group(function (){
    //POST
    Route::post('/posts/', 'PostsController@store');
    Route::post('/posts/{post}', 'PostsController@update');
    //DELETE
    Route::delete('/posts/{post}', 'PostsController@delete');
    Route::delete('/posts/{post}/comments/{commentId}', 'CommentController@delete');
});

//Добавление кометариев
Route::post('/posts/{post}/comments', 'CommentController@store');
