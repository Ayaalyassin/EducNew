<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileStudentRequest;
use App\Http\Requests\UpdateProfileStudentRequest;
use App\Models\ProfileStudent;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileStudentController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    public function getAll()
    {
        try {
            DB::beginTransaction();

            $profile_student = ProfileStudent::all();
            $profile_student->loadMissing(['user']);

            DB::commit();
            return $this->returnData($profile_student, 'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
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
    public function store(ProfileStudentRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();

            $profile_student = $user->profile_student()->create([
                'educational_level' => isset($request->educational_level) ? $request->educational_level : null,
                'description' => isset($request->description) ? $request->description : null,
                'assessing' => 0
            ]);


            DB::commit();
            return $this->returnData($profile_student, 'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {

        try {
            DB::beginTransaction();

            $user = auth()->user()->profile_student()->first();
            $user->loadMissing(['user']);

            DB::commit();
            return $this->returnData($user, 'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }



    public function getById($id)
    {
        try {
            DB::beginTransaction();

            $profile_student = ProfileStudent::find($id);
            if (!$profile_student)
                return $this->returnError("401", 'Not found');
            $profile_student->loadMissing(['user']);

            DB::commit();
            return $this->returnData($profile_student, 'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProfileStudent $profileStudent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileStudentRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();

            $profile_student = $user->profile_student()->first();

            $profile_student->update([
                'educational_level' => isset($request->educational_level) ? $request->educational_level : $profile_student->educational_level,
                'description' => isset($request->description) ? $request->description : $profile_student->description,
            ]);


            DB::commit();
            return $this->returnData($profile_student, 'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();

            $profile_student = $user->profile_student()->first();
            if (!$profile_student)
                return $this->returnError("", 'not found');
            $profile_student->delete();

            DB::commit();
            return $this->returnSuccessMessage('operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }
}
