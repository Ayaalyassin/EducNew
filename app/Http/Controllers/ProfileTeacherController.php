<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileTeacherRequest;
use App\Models\AdsFile;
use App\Models\Domain;
use App\Models\ProfileTeacher;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProfileTeacherRequest;
use App\Models\User;

class ProfileTeacherController extends Controller
{
    use GeneralTrait;

    private $uploadPath = "assets/images/profile_teachers";


    public function index()
    {
        try {
            DB::beginTransaction();

            $profile_teacher = ProfileTeacher::where('status',1)->get();
            $profile_teacher->loadMissing(['user','domains']);

            DB::commit();
            return $this->returnData($profile_teacher, 'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }


    public function store(ProfileTeacherRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();

            $certificate = null;
            if (isset($request->certificate)) {
                $certificate = $this->saveImage($request->certificate, $this->uploadPath);
            }
            $profile_teacher = $user->profile_teacher()->create([
                'certificate' => $certificate,
                'description' => isset($request->description) ? $request->description : null,
                'jurisdiction' => isset($request->jurisdiction) ? $request->jurisdiction : null,
                //'domain' => isset($request->domain) ? $request->domain : null,
                'status' => 0,
                'assessing' => 0
            ]);
            $domains = $request->domains;
            $list_domains = [];
            foreach ($domains as $value) {
                $domain = [
                    'profile_teacher_id' => $profile_teacher->id,
                    'type' => $value,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                array_push($list_domains, $domain);
            }
            Domain::insert($list_domains);

            $profile_teacher->loadMissing('domains');

            DB::commit();
            return $this->returnData($profile_teacher, 'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }


    public function show()
    {
        try {
            DB::beginTransaction();

            $profile_teacher = auth()->user()->profile_teacher()->first();
            $profile_teacher->loadMissing(['user','domains']);

            DB::commit();
            return $this->returnData($profile_teacher, 'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }

    public function getById($id)
    {
        try {
            DB::beginTransaction();

            $profile_teacher = ProfileTeacher::find($id);
            if (!$profile_teacher)
                return $this->returnError("401", 'Not found');
            $profile_teacher->loadMissing(['user','domains']);

            DB::commit();
            return $this->returnData($profile_teacher, 'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }




    public function update(UpdateProfileTeacherRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();

            $certificate = null;
            if (isset($request->certificate)) {
                $certificate = $this->saveImage($request->certificate, $this->uploadPath);
            }

            $profile_teacher = $user->profile_teacher()->first();

            $profile_teacher->update([
                'certificate' => isset($request->certificate) ? $certificate : $profile_teacher->certificate,
                'description' => isset($request->description) ? $request->description : $profile_teacher->description,
                'jurisdiction' => isset($request->jurisdiction) ? $request->jurisdiction : $profile_teacher->jurisdiction,
                //'domain'=>isset($request->domain) ? $request->domain : $profile_teacher->domain,
            ]);

            $profile_teacher->loadMissing('domains');
            DB::commit();
            return $this->returnData($profile_teacher, 'operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }


    public function destroy()
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();

            $profile_teacher = $user->profile_teacher()->first();
            if (!$profile_teacher)
                return $this->returnError("404", 'not found');
            $profile_teacher->delete();
            DB::commit();
            return $this->returnSuccessMessage('operation completed successfully');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError($ex->getCode(), 'Please try again later');
        }
    }
}
