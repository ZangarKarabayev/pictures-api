<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UploadImages extends Controller
{
    public function store(Request $request, $username){

        $validator = Validator::make($request->all(), [
            'userfile' => 'required|mimetypes:image/jpeg,image/png',
        ]);

        if ($validator->fails()) {
            return $validator->errors()->toJson();
        }

        $path = $username . '/' . $request->file('userfile')->getClientOriginalName();

        if(Storage::disk('public')->exists($path)){
            return response('Conflict. Image already exist', 409);
        }

        if($request->file('userfile')){
            $file = $request->file('userfile');
            $upload_folder = "public/$username";
            $filename = $file->getClientOriginalName();
            Storage::putFileAs($upload_folder, $file, $filename);
            return response('file >> ' . $request->file('userfile')->getClientOriginalName() . ' << success uploaded', 200);
        }
    }

    public function index($username, $picture){

        if(!Storage::disk('public')->exists($username . '/' . $picture)){
            return response('Image not found', 404);
        } 
        
        $file = Storage::disk('public')->get($username . '/' . $picture);  
        return (new Response($file, 200))
              ->header('Content-Type', 'image/jpeg');
    }

    public function delete($username, $picture){
        if(!Storage::disk('public')->exists($username . '/' . $picture)){
            return response('Image not found', 404);
        } 
        Storage::disk('public')->delete($username . '/' . $picture);
        return response('Image ' . $picture . ' deleted', 200);
    }
}
