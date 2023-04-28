<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*-----------------------------------Users-----------------------------------*/

//Route to get all users using TableController_api
Route::post('/table/users', 'TableController_api@users');

//Route to select a user using SelectController_api
Route::get('/select/users', 'SelectController_api@users');

//Route to create Users
Route::post('/create/users', 'DocsController@usersCreate')->name('users.createUsers');

//Route to update Users
Route::post('/update/users', 'DocsController@usersUpdate')->name('users.editUsers');

//Route to delete Users
Route::post('/delete/users', 'DocsController@usersDelete')->name('users.deleteUsers');

/*-----------------------------------Docs-----------------------------------*/

//Route to get all docs using TableController_api
Route::post('/table/folder/{id}/docs', 'TableController_api@docs');

//Route to select a docs using SelectController_api
Route::get('/select/docs', 'SelectController_api@docs');

//Route to create Docs 
Route::post('/create/docs', 'DocsController@docsCreate')->name('docs.createDocs');

//Route to update Docs
Route::post('/update/docs', 'DocsController@docsUpdate')->name('docs.editDocs');

//Route to delete Docs
Route::post('/delete/docs', 'DocsController@docsDelete')->name('docs.deleteDocs');

/*----------------------------------Folders----------------------------------*/

//Route to get all folders using TableController_api with a specific id of a folder
Route::post('/table/folders', 'TableController_api@folders');

//Route to select a folder using SelectController_api
Route::get('/select/folders', 'SelectController_api@folders');

//Route to create Folders
Route::post('/create/folders', 'DocsController@createFolders')->name('folders.createFolders');

//Route to update Folders
Route::post('/update/folders', 'DocsController@updateFolders')->name('folders.editFolders');

//Route to delete Folders
Route::post('/delete/folders', 'DocsController@deleteFolders')->name('folders.deleteFolders');

/*-----------------------------------PDF-----------------------------------*/

//Route to get the pdf file asked by the user
Route::get('/pdf/view', 'DocsController@getPdf')-> name('pdf.getPdf');

/*-----------------------------------Categories-----------------------------------*/

//Route to get all categories using TableController_api
Route::post('/table/categories', 'TableController_api@categories');

//Route to select a category using SelectController_api
Route::get('/select/categories', 'SelectController_api@categories');

//Route to create Categories
Route::post('/create/categories', 'DocsController@categoriesCreate')->name('categories.createCategories');

//Route to update Categories
Route::post('/update/categories', 'DocsController@categoriesUpdate')->name('categories.editCategories');

//Route to delete Categories
Route::post('/delete/categories', 'DocsController@categoriesDelete')->name('categories.deleteCategories');


