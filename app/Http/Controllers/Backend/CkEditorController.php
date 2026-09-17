<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\Backend\Ckeditor\UploadRequest;

class CkEditorController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(UploadRequest $request)
    {
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.md5(time()).'.'.$extension;
            $request->file('upload')->move(storage_path('app/public/upload'), $fileName);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('storage/upload/'.$fileName);
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }

    }
}
