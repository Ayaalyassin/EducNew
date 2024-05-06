<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;

trait GeneralTrait
{


    public function returnError($errNum, $msg)
    {
        return response()->json([
            'status' => false,
            'errNum' => $errNum,
            'message' => $msg
        ],400);
    }


    public function returnSuccessMessage($msg = "", $errNum = "200")
    {
        return response()->json([
            'status' => true,
            'errNum' => $errNum,
            'message' => $msg
        ],200);
    }

    public function returnData($value, $msg = "successfully")
    {
        return response()->json([
            'status' => true,
            'errNum' => "200",
            'message' => $msg,
            'data' => $value
        ],200);
    }


    public function returnValidationError($code = "422", $validator)
    {
        return $this->returnError($code, $validator->errors());
    }



    function saveAnyFile($file, $folder)
    {
        try {
            $file_extension = $file->getClientOriginalExtension();
            $file_name = time() . rand() . '.' . $file_extension;
            $file->move($folder, $file_name);
            return $folder . '/' . $file_name;
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), "Error in file save ");
        }
    }


    public function deleteFile($file)
    {

        try {
            if (\File::exists(public_path($file))) {
                unlink($file);
            }
            return null;
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), "This Image Not found");
        }
    }

    function saveImage($photo, $folder)
    {
        try {
            $file_extension = $photo->getClientOriginalExtension();
            $file_name = time() . rand() . '.' . $file_extension;
            $photo->move($folder, $file_name);
            return $folder . '/' . $file_name;
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), "Error in image save ");
        }
    }

    function saveImageByName($photo, $folder,$name)
    {
        try {
            $file_extension = $photo->getClientOriginalExtension();
            $file_name = $name. '.' . $file_extension;
            $photo->move($folder, $file_name);
            return $folder . '/' . $file_name;
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), "Error in image save ");
        }
    }
    public function deleteImage($photo)
    {

        try {
            if (\File::exists(public_path($photo))) {
                unlink($photo);
            }
        } catch (\Exception $ex) {
            throw new HttpResponseException($this->returnError($ex->getCode(), "This image Not found"));
        }
    }

    function saveVideo($video, $folder)
    {
        try {
            $file_extension = $video->getClientOriginalExtension();
            $file_name = time() . rand() . '.' . $file_extension;
            $video->move($folder, $file_name);
            return $folder . '/' . $file_name;
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), "Error in video save ");
        }
    }


    public function deleteVideo($video)
    {
        try {
            if (\File::exists(public_path($video))) {
                unlink($video);
            }
            return null;
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), "This video Not found");
        }
    }

    function deleteFolder($path)
    {
        try {
            if (\File::exists(public_path($path))) \File::deleteDirectory(public_path($path));
        } catch (\Exception $ex) {
            throw new HttpResponseException($this->returnError($ex->getCode(), "This Folder Not found"));
        }
    }



}
