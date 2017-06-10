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
Route::group(['namespace' => 'Admin'], function () {
    Route::get('/', 'IndexController@index');
    /*
     *
     */
    Route::get('book', 'BookController@index');
    Route::get('book/{id}/edit', 'BookController@edit');
    Route::get('book/{id}/handle', 'BookController@handle');
    Route::get('book/{id}/image', 'BookController@image');
    Route::post('book/{id}', 'BookController@update');
    Route::delete('book/{id}', 'BookController@destroy');
    /*
     *
     */
    Route::get('chapter', 'ChapterController@index');
    Route::get('chapter/{id}/edit', 'ChapterController@edit');
    Route::post('chapter/{id}', 'ChapterController@update');
    Route::delete('chapter/{id}', 'ChapterController@destroy');
    /*
     *
     */
    Route::get('section', 'SectionController@index');
    Route::get('section/{id}/edit', 'SectionController@edit');
    Route::post('section/{id}', 'SectionController@update');
    Route::delete('section/{id}', 'SectionController@destroy');
    /*
     *
     */
    Route::get('catalog', 'CatalogController@index');
    Route::post('catalog', 'CatalogController@store');
});