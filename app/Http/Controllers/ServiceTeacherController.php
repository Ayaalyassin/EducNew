<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateServiceTeacherRequest;
use App\Models\ProfileTeacher;
use App\Models\ServiceTeacher;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ServiceTeacherRequest;
use App\Models\User;

class ServiceTeacherController extends Controller
{
    use GeneralTrait;


    public function index($teacher_id)
    {
        try {
            //$profile_teacher=User::find($teacher_id)->profile_teacher()->first();
            $profile_teacher=ProfileTeacher::find($teacher_id);
            if (!$profile_teacher) {
                return $this->returnError("401",'user Not found');
            }
            $service_teachers=$profile_teacher->service_teachers()->get();
            return $this->returnData($service_teachers,'operation completed successfully');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), "Please try again later");
        }
    }



    public function store(ServiceTeacherRequest $request)
    {
        try {
            DB::beginTransaction();

            $profile_teacher=auth()->user()->profile_teacher()->first();

            $exist=$profile_teacher->service_teachers()->where('type',$request->type)->first();
            if($exist)
                return $this->returnError('500', 'the service already exist');

            $service_teacher= $profile_teacher->service_teachers()->create([
                'price' => $request->price,
                'type' =>$request->type,
            ]);


            DB::commit();
            return $this->returnData($service_teacher,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }



    public function show($id)
    {
        try {
            DB::beginTransaction();

            $ServiceTeacher= ServiceTeacher::find($id);
            if (!$ServiceTeacher) {
                return $this->returnError("401",'ServiceTeacher Not found');
            }

            DB::commit();
            return $this->returnData($ServiceTeacher,'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }



    public function update(UpdateServiceTeacherRequest $request,$id)
    {
        try {
            DB::beginTransaction();
            $profile_teacher=auth()->user()->profile_teacher()->first();

            $service_teacher=$profile_teacher->service_teachers()->find($id);

            if(!$service_teacher)
                return $this->returnError("", 'not found');

            $service_teacher->update([
                'price' => isset($request->price)? $request->price :$service_teacher->price,
                'type' =>isset($request->type)? $request->type :$service_teacher->type,
            ]);


            DB::commit();
            return $this->returnData($service_teacher,'operation completed successfully');
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
            $profile_teacher=auth()->user()->profile_teacher()->first();
            $service_teacher=$profile_teacher->service_teachers()->find($id);
            if(!$service_teacher)
                return $this->returnError("", 'not found');
            $service_teacher->delete();

            DB::commit();
            return $this->returnSuccessMessage('operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }

    public function getMyService()
    {
        try {
            $profile_teacher =auth()->user()->profile_teacher()->first();

            $service_teachers=[];
            if($profile_teacher)
                $service_teachers=$profile_teacher->service_teachers()->get();

            return $this->returnData($service_teachers, 'operation completed successfully');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), "Please try again later");
        }
    }
}
