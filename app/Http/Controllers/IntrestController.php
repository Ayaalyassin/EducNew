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
    /**
     * Display a listing of the resource.
     */
    public function getMyIntrests()
    {
        try {
            $profile_student=auth()->user()->profile_student()->first();
            if (!$profile_student) {
                return $this->returnError("401",'user Not found');
            }
            $intrests=$profile_student->intrests()->get();
            return $this->returnData($intrests,'operation completed successfully');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(Intrest $intrest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Intrest $intrest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IntrestRequest $request,$id)
    {
        try {
            DB::beginTransaction();
            $profile_student=auth()->user()->profile_student()->first();

            $intrest=$profile_student->intrests()->find($id);
            if(!$intrest)
                return $this->returnError("", 'not found');
            $intrest->update([
                'type' =>$request->type,
            ]);

            DB::commit();
            return $this->returnData($intrest,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $profile_student=auth()->user()->profile_student()->first();
            $intrest=$profile_student->intrests()->find($id);
            $intrest->delete();

            DB::commit();
            return $this->returnSuccessMessage('operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }
}
