<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UploadImages extends Controller
{
    protected  $formats;

    public function __construct()
    {
        $this->formats = ['jpg', 'png', 'gif'];
    }
    public function store(Request $request, $username, $subfolder = ''){

        $validator = Validator::make($request->all(), [
            'userfile' => 'required|mimetypes:image/jpeg,image/png',
        ]);

        if ($validator->fails()) {
            return $validator->errors()->toJson();
        }

        $path2 = $username . '/' . $subfolder . '/' . $request->file('userfile')->getClientOriginalName();

        if($request->file('userfile')){
            $file = $request->file('userfile');
            if ($subfolder != ''){
                $upload_folder = "public/$username/$subfolder";
            }else{
                $upload_folder = "public/$username";
            }
                    if(Storage::disk('public')->exists($path2)){
                        Storage::disk('public')->delete($path2);
                        Storage::putFileAs($upload_folder, $file, $file->getClientOriginalName());
                        return response('file >> ' . url()->current() . '/' . $file->getClientOriginalName() . ' << success reloaded', 200);
                    }else{
                    $filename = $file->getClientOriginalName();
                    Storage::putFileAs($upload_folder, $file, $filename);
                    return response('file >> ' . url()->current() . '/' . $file->getClientOriginalName() . ' << success uploaded', 200);
                    }

        }
    }

    public function index($username, $subfolder = '', $picture = ''){
        $path2 = "$username/$subfolder";       
        $img = '';
        

        foreach($this->formats as $format){
            if(Storage::disk('public')->exists("$path2/$picture.$format") && $subfolder != ''){
                  $img = "$path2/$picture.$format";
            }else{
                continue;
            }
        }    
        
        if($img != ''){
            $file = Storage::disk('public')->get($img);  
            return (new Response($file, 200))
                    ->header('Content-Type', 'image/jpeg');    
        }else{
            return response('Image not found');
        }

    }

    public function delete($username, $subfolder = '', $picture= ''){
        // $path = "$username". '/';
        $path2 = $username . '/' . $subfolder . '/';
        
        // if(!Storage::disk('public')->exists($path . $picture) && $subfolder == ''){
        //     return response('Image not found', 404);
        // } 
         
        $img = '';

        foreach($this->formats as $format){
            if(Storage::disk('public')->exists("$path2/$picture.$format") && $subfolder != ''){
                  $img = "$path2/$picture.$format";
            }else{
                continue;
            }
        }  
        if($img != ''){
            Storage::disk('public')->delete($img);
            return response('Image ' . $picture . ' deleted', 200);   
        }else{
            return response('File not found', 400);
        }

        // if($subfolder == ''){
        //     Storage::disk('public')->delete($img);
        //     return response('Image ' . $picture . ' deleted', 200);
        // }else{
        // }

    }
}
