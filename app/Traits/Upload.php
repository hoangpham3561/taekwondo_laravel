<?php
namespace App\Traits;
trait Upload
{
    protected $name;
    protected $fileExtension;
    protected $dir;
    protected $requestName;
    protected $extension;

    public function doUpload($fileExtension, $dir, $requestName)
    {
        try {
            $this->requestName 	= $requestName;
            $this->dir 			= $dir;
            $file = request()->file($this->requestName);
            if(request()->hasFile($this->requestName)){
                if ($file->isValid()) {
                    $this->fileExtension 	= explode('|', $fileExtension);
                    $this->extension 	= $file->getClientOriginalExtension();
                    if(in_array($this->extension, $this->fileExtension)){
                        $this->name = md5(time() . $file->getClientOriginalName()) . '.' . $this->extension;
                        $file->move(storage_path('app/public/') . $this->dir, $this->name);
                        return $this->name;
                    }else{
                        throw new \Exception("Invalid extension");
                    }
                }
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

    }



    public function deleteImage($dir, $fileName) {
        try {
            $this->name 	= $fileName;
            $this->dir 			= $dir;
            @unlink(storage_path('app/public/') . $this->dir . '/' . $this->name);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
