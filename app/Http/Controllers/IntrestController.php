<?php

namespace App\Http\Controllers;

use App\Models\Intrest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\IntrestRequest;

class IntrestController extends Controller
{
    use GeneralTrait;

    public function getMyIntrests()
    {
        try {
            $profile_student=auth()->user()->profile_student()->first();
            $intrests=[];
            if ($profile_student)
                $intrests=$profile_student->intrests()->get();

            return $this->returnData($intrests,'operation completed successfully');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }


    public function store(IntrestRequest $request)
    {
        try {
            DB::beginTransaction();

            $profile_student=auth()->user()->profile_student()->first();

            $intrest= $profile_student->intrests()->create([
                'type' => $request->type,
            ]);

            DB::commit();
            return $this->returnData($intrest,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }


    public function show(Intrest $intrest)
    {
        //
    }


    public function update(IntrestRequest $request,$id)
    {
        try {
            DB::beginTransaction();
            $profile_student=auth()->user()->profile_student()->first();

            $intrest=$profile_student->intrests()->find($id);
            if(!$intrest)
                return $this->returnError("404", 'not found');
            $intrest->update([
                'type' =>$request->type,
            ]);

            DB::commit();
            return $this->returnData($intrest,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError("500", 'Please try again later');
        }
    }


    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $profile_student=auth()->user()->profile_student()->first();
            $intrest=$profile_student->intrests()->find($id);
            if(!$intrest)
                return $this->returnError("404", 'not found');
            $intrest->delete();

            DB::commit();
            return $this->returnSuccessMessage('operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }
}
