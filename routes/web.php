<?php

use App\Models\Doc;
use App\Models\Folder;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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

/*-----------------------------------Login-----------------------------------*/

Route::post('/login', 'LoginController@login')->name('login');

Route::post('/logout', 'LoginController@logout')->name('logout');

Route::get('/login', function () {
    return view('login');
}) -> name('loginPage');

// User logged in
Route::middleware('perms')->group(function(){
    
    Route::get('/admin/qrcodes',function() {
        return view('folders');
    });
    
    Route::get('/admin/qrcodes/{id}/docs', function($id) {
        return view('docs', [
        'id' => $id
        ]);
    });
    
    Route::get('/admin', function () {
        return view('index');
    })->name('routeHomepage');

    Route::get('/admin/categories', function () {
        return view('categories');
    })->name('categories');

});

// User logged in and is admin
Route::middleware('perms:admin')->group(function(){

    Route::get('/admin/users', function () {
        return view('users');
    });
    
});


Route::get('qr/{id}', function($id) {
    $folder = Folder::find($id) or abort(404);
    return view('userView', [
        'id' => $id,
        'name' => $folder->folder_name
    ]);
});

Route::get('qr/{id}/{doc}', function($id, $doc) {
    $document = Doc::where('doc_path', $doc)
        ->where('folder_id', $id)
        ->first() or abort(404);

    // get the pdf from the storage and return it to the view
    $pdf = Storage::get("/public/folders/$id/$doc");

  // Show the pdf
    return response($pdf, 200)->header('Content-Type', 'application/pdf');
});

Route::get('/download-pdf{id}', function($id){
    return view('showPdf',[
        'id' => $id
    ]);
});