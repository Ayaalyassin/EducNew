<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAdsRequest;
use App\Models\Ads;
use App\Models\ProfileTeacher;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\AdsRequest;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;

class AdsController extends Controller
{
    use GeneralTrait;

    private $uploadPath = "assets/images/ads";

    public function index()
    {
        try {
            $ads = Ads::all();
            return $this->returnData($ads, 'operation completed successfully');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), "Please try again later");
        }
    }

    public function getAdsTeacher($teacherId)
    {
        try {
//            $user = User::find($userId);
//            $profile_teacher=$user->profile_teacher()->first();
//            $ads=$profile_teacher->ads()->get();
            $profile_teacher = ProfileTeacher::find($teacherId);
            $ads=$profile_teacher->ads()->get();


            return $this->returnData($ads, 'operation completed successfully');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), "Please try again later");
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
    public function store(AdsRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();

            $file = null;
            if (isset($request->file)) {
                $file = $this->saveImage($request->file, $this->uploadPath);
            }

            $profile_teacher=$user->profile_teacher()->first();
            $ads =$profile_teacher->ads()->create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'number_students' => $request->number_students,
                'file' => $file,
                'place'=>$request->place
            ]);


            DB::commit();
            return $this->returnData($ads, 'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */

    public function getById($id)
    {
        try {
            $data = Ads::where('id', $id)->first();
            if (!$data) {
                return $this->returnError('Not found', 401);
            }
            return $this->returnData($data, 'operation completed successfully');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ads $ads)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdsRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();

            $profile_teacher = $user->profile_teacher()->first();
            $ads=$profile_teacher->ads()->find($id);

            if (!$ads)
                return $this->returnError("", 'not found');

            $file = null;
            if (isset($request->file)) {
                $file = $this->saveImage($request->file, $this->uploadPath);
            }
            $ads->update([
                'name' => isset($request->name) ? $request->name : $ads->name,
                'description' => isset($request->description) ? $request->description : $ads->description,
                'price' => isset($request->price) ? $request->price : $ads->price,
                'number_students' => isset($request->number_students) ? $request->number_students : $ads->number_students,
                'file' => isset($request->file) ? $file : $ads->file,
                'place'=> isset($request->place) ? $request->place : $ads->place
            ]);

            DB::commit();
            return $this->returnData($ads, 'operation completed successfully');
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
            $profile_teacher = auth()->user()->profile_teacher()->first();

            $ads = $profile_teacher->ads()->find($id);
            if (!$ads)
                return $this->returnError("", 'not found');
            if (isset($ads->file)) {
                $this->deleteImage($ads->file);
            }

            $ads->delete();
            DB::commit();
            return $this->returnSuccessMessage('operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }

    public function getMyAds()
    {
        try {
            $profile_teacher =auth()->user()->profile_teacher()->first();

            $ads=[];
            if($profile_teacher)
                $ads=$profile_teacher->ads()->get();

            return $this->returnData($ads, 'operation completed successfully');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), "Please try again later");
        }
    }
}
