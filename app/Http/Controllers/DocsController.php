<?php

namespace App\Http\Controllers;

use App\Models\Doc;
use App\Models\User;
use App\Models\Folder;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;


class DocsController extends Controller
{

/*******************************Categories*****************************/

        // Create Category
        function categoriesCreate (Request $request) {
            $category = new Category;
            $category->category_name = $request->category_name;
            $category->save();
            return response()->json([
                'message' => 'Category created',
                'category' => $category
            ], 200);
        }

        // Update Category
        function categoriesUpdate (Request $request) {
            $category = Category::find($request->category_id);
            $category->category_name = $request->category_name;
            $category->save();
            return response()->json([
                'message' => 'Category updated',
                'category' => $category
            ], 200);
        }

        // Delete Category
        function categoriesDelete (Request $request) {
            $category = Category::find($request->category_id);
            $category->delete();
            return response()->json([
                'message' => 'Category deleted',
                'category' => $category
            ], 200);
        }

/*-------------------------------Docs---------------------------------*/    
    // Create Docs
    function docsCreate (Request $request) {

        // get the file from the request
        $file = $request->file('pdf_file');

        // if the file is not .pdf, return error
        if($file->getClientOriginalExtension() != 'pdf'){
            return response()->json([
                'message' => 'File is not a pdf',
            ], 400);
        }

        $doc = new Doc;
        $doc->doc_name = $request->doc_name;
        $doc->doc_path = 'folders/' . $request->folder_id . '/' . $doc->doc_id . '.pdf';
        $doc->doc_observation = $request->doc_observation;
        $doc->folder_id = $request->folder_id;
        $doc->save();
        $doc->doc_path = $doc->doc_id . '-' . $file->getClientOriginalName();
        $doc->save();

        // Encrypt the file content before saving
        // $encryptedContent = Crypt::encrypt(file_get_contents($file->getRealPath()));
        // Storage::put('public/folders/' . $request->folder_id . '/' . $doc->doc_path, $encryptedContent);

        // get content from file
        $file = file_get_contents($file->getRealPath());

        Storage::put('public/folders/' . $request->folder_id . '/' . $doc->doc_path, $file);

        return response()->json([
            'message' => 'Doc created successfully',
            'doc' => $doc
        ], 201);
    }

    // Update Docs
    function docsUpdate (Request $request) {

    if($request->file('pdf_file') != null){

            // get the file from the request
            $file = $request->file('pdf_file');

            // if the file is not .pdf, return error
            if($file->getClientOriginalExtension() != 'pdf'){
                return response()->json([
                    'message' => 'File is not a pdf',
                ], 400);
            }
        }

        $doc = Doc::find($request->doc_id);
        if($request->file('pdf_file') != null){

        Storage::delete($doc->doc_path);
        $doc->doc_path = $doc->doc_id . '-' . $file->getClientOriginalName();

         // Encrypt the file content before saving
        // $encryptedContent = Crypt::encrypt(file_get_contents($file->getRealPath()));
        // Storage::put('public/folders/' . $request->folder_id . '/' . $doc->doc_path, $encryptedContent);
        Storage::put('public/folders/' . $request->folder_id . '/' . $doc->doc_path, $file);

        }
        
        $doc->doc_name = $request->doc_name;
        $doc->doc_observation = $request->doc_observation;
        $doc->save();


        return response()->json([
            'message' => 'Doc updated successfully',
            'docs' => $doc
        ], 200);
        
    }

    // Delete Docs
    function docsDelete (Request $request) {

        $docs = Doc::find($request->doc_id);
        // Delete the file in storage/app/public/folders/{folder_id}/{doc_id}.pdf
        Storage::delete('/public/folders/' . $docs->folder_id . '/' . $docs->doc_path);

        $docs->delete();
        return response()->json([
            'message' => 'Docs deleted successfully',
            'docs' => $docs
        ], 200);
    }

/*-------------------------------Folders---------------------------------*/    

    function createFolders (Request $request) {
        $f = new Folder;
        $f->folder_name = $request->folder_name;
        $f->folder_local = $request->folder_local;
        $f->folder_description = $request->folder_description;
        $f->category_id = $request->category_id;
        $f->folder_path = 'folders/' . $f->folder_id . '/';
        $f->save();
        $f->folder_path = 'folders/' . $f->folder_id . '/';
        $f->save();

        


        // Create a folder in storage/app/public/folders/
        Storage::makeDirectory('public/folders/' . $f->folder_id);

        return response()->json([
            'message' => 'folders created successfully',
            'folders' => $f
        ]);
    }

    function updateFolders (Request $request) {
        $f = Folder::find($request->folder_id);
        $f->folder_name = $request->folder_name;
        $f->folder_description = $request->folder_description;
        $f->category_id = $request->category_id;
        $f->save();
        return response()->json([
            'message' => 'Folder updated successfully',
            'folders' => $f
        ]);
    }

    function deleteFolders (Request $request) {
        $f = Folder::find($request->folder_id);

        // Delete a folder in storage/app/public/folders/
        Storage::deleteDirectory('public/folders/' . $f->folder_id);

        //Delete all docs in the folder 
        $docs = Doc::where('folder_id', $f->folder_id)->get();
        foreach ($docs as $doc) {
            $doc->delete();
        }
    
        $f->delete();

        return response()->json([
            'message' => 'Folder deleted successfully',
            'folders' => $f
        ]);
    }

    /*-------------------------PDF FILE------------------------------*/

    function getPdf(Request $request) {


        // PDF file is in href="/qr/${folder_id}/${doc_path}
        $pdfUrl = 'qr/' . $request->folder_id . '/' . $request->doc_path;
        $encryptedURL = Crypt::encryptString($pdfUrl);
        
        // redirect to the pdf file
        dd($encryptedURL . '...........' . $pdfUrl);

    }

    /*-------------------------Users---------------------------------*/

    function usersCreate (Request $request) {
        $u = new User;
        $u->fill($request->input());
        $u->user_pass = Hash::make($request->input('user_pass'));
        $u->user_super = isset($request->user_super);
        $u->user_ad = isset($request->user_ad);

        // if user pass is empty and user_ad is not set then return error
        if($request->input('user_pass') == null && $request->input('user_ad') == null){
            return response()->json([
                'message' => 'User pass is empty and user_ad is not set',
            ], 400);
        }

        $u->save();

        return response()->json($u);
    }

    function usersUpdate (Request $request) {
        $u = User::find($request->user_id);
        $u->user_name = $request->user_name;
        // If the password is not empty, update it
        if($request->user_pass != null){
            $u->user_pass = Hash::make($request->user_pass);
        }
        $u->user_super = isset($request->user_super);
        $u->user_ad = isset($request->user_ad);

        // if user pass is empty and user_ad is not set then return error
        if($request->input('user_pass') == null && $request->input('user_ad') == null){
            return response()->json([
                'message' => 'User pass is empty and user_ad is not set',
            ], 400);
        }

        $u->save();
        
        return response()->json($u);
    }

    function usersDelete (Request $request) {
        $u = User::find($request->user_id);
        $u->delete();
        return response()->json($u);
    }
}
