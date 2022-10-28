<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UploadImages extends Controller
{
    public function store(Request $request, $username, $subfolder = ''){

        $validator = Validator::make($request->all(), [
            'userfile' => 'required|mimetypes:image/jpeg,image/png',
        ]);

        if ($validator->fails()) {
            return $validator->errors()->toJson();
        }

        $path = $username . '/' . $request->file('userfile')->getClientOriginalName();
        $path2 = $username . '/' . $subfolder . '/' . $request->file('userfile')->getClientOriginalName();

        if(Storage::disk('public')->exists($path) && $subfolder == ''){
            return response('Conflict. Image already exist', 409);
        }

        if(Storage::disk('public')->exists($path2) && $subfolder != ''){
            return response('Conflict. Image already exist', 409);
        }

        if($request->file('userfile')){
            $file = $request->file('userfile');
            if ($subfolder != ''){
                $upload_folder = "public/$username/$subfolder";
            }else{
                $upload_folder = "public/$username";
            }
            $filename = $file->getClientOriginalName();
            Storage::putFileAs($upload_folder, $file, $filename);
            return response('file >> ' . $request->file('userfile')->getClientOriginalName() . ' << success uploaded', 200);
        }
    }

    public function index($username, $subfolder = '', $picture = ''){
        $path = "$username". '/';
        $path2 = $username . '/' . $subfolder . '/';
        if(!Storage::disk('public')->exists($path) && $subfolder == ''){
            return response('Image not found', 404);
        } 

        if(!Storage::disk('public')->exists($path2 . '/' . $picture) && $subfolder != ''){
            return response('Image not found', 404);
        } 

        if($subfolder == null){          
            $file = Storage::disk('public')->get($path . $picture);  
            return (new Response($file, 200))
                ->header('Content-Type', 'image/jpeg');   
        }else{
            $file = Storage::disk('public')->get($path2 . $picture);  
            return (new Response($file, 200))
                ->header('Content-Type', 'image/jpeg');    
        }
    }

    public function delete($username, $subfolder = '', $picture= ''){
        $path = "$username". '/';
        $path2 = $username . '/' . $subfolder . '/';
        
        if(!Storage::disk('public')->exists($path . $picture) && $subfolder == ''){
            return response('Image not found', 404);
        } 

        if(!Storage::disk('public')->exists($path2 . $picture) && $subfolder != ''){
            return response('Image not found', 404);
        } 
        if($subfolder == ''){
            Storage::disk('public')->delete($path . $picture);
            return response('Image ' . $picture . ' deleted', 200);
        }else{
            Storage::disk('public')->delete($path2 . $picture);
            return response('Image ' . $picture . ' deleted', 200);
        }

    }
}
