<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BlockController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileStudentAdsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileTeacherController;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\AppointmentAvailableController;
use App\Http\Controllers\AppointmentTeacherStudentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\GovernorController;
use App\Http\Controllers\ProfileStudentController;
use App\Http\Controllers\IntrestController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QualificationCourseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RequestCompleteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceTeacherController;
use App\Http\Controllers\Teacher\CalendarController;
use App\Http\Controllers\Teacher\CompleteTeacherController;
use App\Http\Controllers\TeachingMethodController;
use App\Http\Controllers\TeachingMethodUserController;
use App\Http\Controllers\User\CompleteController;
use App\Http\Controllers\User\CompleteStudentController;
use App\Http\Controllers\User\LockHourController;
use App\Http\Controllers\WalletController;
use App\Models\Wallet;
use Spatie\Permission\Contracts\Permission;
use GuzzleHttp\Middleware;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/login/google', [AuthController::class, 'redirectToGoogle']);
Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forgetPassword', [AuthController::class, 'forgetPassword']);
Route::post('checkCode', [AuthController::class, 'checkCode']);
Route::post('passwordNew', [AuthController::class, 'passwordNew']);

Route::group(['middleware' => ['jwt.verify']], function () {

    Route::post('resetPassword', [AuthController::class, 'resetPassword']);

    Route::group(['middleware' => ['hasRole:teacher']], function () {

        Route::group(['prefix' => 'profile_teacher'], function () {
            Route::post('store', [ProfileTeacherController::class, 'store']);
            Route::post('update', [ProfileTeacherController::class, 'update']);
            Route::get('getmyProfile', [ProfileTeacherController::class, 'show']);
        });
    });

    Route::group(['middleware' => ['hasRole:student']], function () {

        Route::group(['prefix' => 'profile_teacher'], function () {
            Route::get('getById/{id}', [ProfileTeacherController::class, 'getById']);
            Route::get('index', [ProfileTeacherController::class, 'index']);
        });
    });


    Route::group(['middleware' => ['hasRole:student']], function () {

        Route::group(['prefix' => 'profile_student'], function () {
            Route::post('store', [ProfileStudentController::class, 'store']);
            Route::post('update', [ProfileStudentController::class, 'update']);
            Route::get('getmyProfile', [ProfileStudentController::class, 'show']);
        });
    });

    Route::group(['middleware' => ['hasRole:teacher']], function () {
        Route::group(['prefix' => 'profile_student'], function () {
            Route::get('getById/{id}', [ProfileStudentController::class, 'getById']);
            Route::get('getAll', [ProfileStudentController::class, 'getAll']);
        });
    });
    Route::group(['middleware' => ['hasRole:teacher']], function () {

        Route::group(['prefix' => 'ads'], function () {
            Route::post('store', [AdsController::class, 'store'])->middleware('profileTeacher');
            Route::post('update/{id}', [AdsController::class, 'update']);
            Route::delete('delete/{id}', [AdsController::class, 'destroy']);
            Route::get('getMyAds', [AdsController::class, 'getMyAds']);
        });
    });

    Route::get('ads/getById/{id}', [AdsController::class, 'getById']);

    Route::group(['middleware' => ['hasRole:student']], function () {
        Route::group(['prefix' => 'ads'], function () {
            Route::get('getAll', [AdsController::class, 'index']);
            Route::get('getAdsTeacher/{id}', [AdsController::class, 'getAdsTeacher']);
        });
    });

    Route::group(['middleware' => ['hasRole:admin']], function () {
        Route::group(['prefix' => 'employee'], function () {
            Route::get('getAll', [EmployeeController::class, 'getAll']);
            Route::post('store', [EmployeeController::class, 'createEmployee']);
            Route::post('update/{id}', [EmployeeController::class, 'updateEmployee']);
            Route::get('getById/{id}', [EmployeeController::class, 'getById']);
            Route::delete('delete/{id}', [EmployeeController::class, 'delete']);
        });
    });

    Route::group(['middleware' => ['hasRole:student']], function () {
        Route::group(['prefix' => 'evaluation'], function () {
            Route::post('store', [EvaluationController::class, 'store'])->middleware('profileStudent');
            Route::delete('delete/{id}', [EvaluationController::class, 'destroy']);
        });

        Route::group(['prefix' => 'intrest'], function () {
            Route::post('store', [IntrestController::class, 'store'])->middleware('profileStudent');
            Route::post('update/{id}', [IntrestController::class, 'update']);
            Route::delete('delete/{id}', [IntrestController::class, 'destroy']);
            Route::get('getMyIntrests', [IntrestController::class, 'getMyIntrests']);
        });
    });

    Route::group(['middleware' => ['hasRole:teacher']], function () {

        Route::group(['prefix' => 'note'], function () {
            Route::post('store', [NoteController::class, 'store'])->middleware('profileTeacher');;
            Route::delete('delete/{id}', [NoteController::class, 'destroy']);
            Route::get('getStudentsNotes', [NoteController::class, 'index']);
        });
    });

    Route::group(['middleware' => ['hasRole:admin']], function () {

        Route::group(['prefix' => 'permission'], function () {
            Route::post('store', [PermissionController::class, 'create']);
            Route::post('update/{id}', [PermissionController::class, 'update']);
            Route::get('getall', [PermissionController::class, 'getAll']);
            Route::get('getById/{id}', [PermissionController::class, 'getById']);
            Route::delete('delete/{id}', [PermissionController::class, 'delete']);
        });
    });

    Route::group(['prefix' => 'report'], function () {
        Route::get('get', [ReportController::class, 'index']);
        Route::post('report_student', [ReportController::class, 'report_student'])->middleware(['hasRole:teacher', 'profileTeacher']);;
        Route::post('report_teacher', [ReportController::class, 'report_teacher'])->middleware(['hasRole:student', 'profileStudent']);
    });

    Route::group(['middleware' => ['hasRole:admin']], function () {

        Route::group(['prefix' => 'role'], function () {
            Route::post('store', [RoleController::class, 'create']);
            Route::post('update/{id}', [RoleController::class, 'update']);
            Route::delete('delete/{id}', [RoleController::class, 'delete']);
            Route::get('getAll', [RoleController::class, 'getAll']);
            Route::get('getById/{id}', [RoleController::class, 'getById']);
        });
    });

    Route::group(['middleware' => ['hasRole:teacher']], function () {

        Route::group(['prefix' => 'ServiceTeacher'], function () {
            Route::post('store', [ServiceTeacherController::class, 'store'])->middleware('profileTeacher');;
            Route::post('update/{id}', [ServiceTeacherController::class, 'update']);
            Route::delete('delete/{id}', [ServiceTeacherController::class, 'destroy']);
            Route::get('getMyService', [ServiceTeacherController::class, 'getMyService']);
        });
    });

    Route::get('ServiceTeacher/getById/{id}', [ServiceTeacherController::class, 'show']);

    Route::group(['middleware' => ['hasRole:student']], function () {
        Route::get('ServiceTeacher/getAll/{id}', [ServiceTeacherController::class, 'index']);
    });


    Route::group(['middleware' => ['hasRole:teacher']], function () {
        Route::group(['prefix' => 'TeachingMethod'], function () {
            Route::post('store', [TeachingMethodController::class, 'store'])->middleware('profileTeacher');;
            Route::post('update/{id}', [TeachingMethodController::class, 'update']);
            Route::delete('delete/{id}', [TeachingMethodController::class, 'destroy']);
            Route::get('getMyTeachingMethod', [TeachingMethodController::class, 'getMyTeachingMethod']);
        });
    });

    Route::get('TeachingMethod/getById/{id}', [TeachingMethodController::class, 'show']);

    Route::group(['middleware' => ['hasRole:student']], function () {
        Route::get('TeachingMethod/getAll/{id}', [TeachingMethodController::class, 'index']);
    });

    Route::group(['middleware' => ['hasRole:student']], function () {
        Route::group(['prefix' => 'TeachingMethodUser'], function () {
            Route::post('store', [TeachingMethodUserController::class, 'store'])->middleware('profileStudent');
            Route::delete('delete/{id}', [TeachingMethodUserController::class, 'destroy']);
            Route::get('getMyTeachingMethod', [TeachingMethodUserController::class, 'getMyTeachingMethod']);
        });
    });



    //  khadr
    Route::group(['prefix' => 'transactions-wallet'], function () {

        Route::group(['middleware' => ['hasRole:admin']], function () {
            Route::get('get-request-charge', [GovernorController::class, 'get_request_charge']);
            Route::get('get-request-recharge', [GovernorController::class, 'get_request_recharge']);
            Route::delete('delete-request/{id}', [GovernorController::class, 'destroy']);
            Route::get('accept_request_charge/{id}', [GovernorController::class, 'accept_request_charge']);
            Route::get('accept_request_recharge/{id}', [GovernorController::class, 'accept_request_recharge']);
        });
        // Route::group(['middleware' => ['hasRole:student', 'hasRole:teacher']], function () {
        Route::post('store', [GovernorController::class, 'store']);
        Route::get('show-my-request', [GovernorController::class, 'show']);
        // });
    });

    //  khader
    Route::group(['prefix' => 'request-complete'], function () {
        Route::group(['middleware' => ['hasRole:teacher']], function () {
            Route::post('store', [CompleteTeacherController::class, 'store']);
            Route::post('update', [CompleteTeacherController::class, 'update']);
        });
        Route::group(['middleware' => ['hasRole:admin']], function () {
            Route::get('get', [CompleteTeacherController::class, 'index']);
            Route::delete('delete-request-complete/{id}', [CompleteTeacherController::class, 'destroy']);
            Route::get('accept-request-complete-teacher/{id}', [CompleteTeacherController::class, 'accept_request_complete_teacher']);
        });
    });

    // Admin khader
    Route::group(['prefix' => 'request-join', 'middleware' => ['hasRole:admin']], function () {
        Route::get('get', [AdminController::class, 'index']);
        Route::delete('delete-request-join/{id}', [AdminController::class, 'destroy']);
        Route::get('accept-request-join/{id}', [AdminController::class, 'accept_request_teacher']);
        Route::get('count-student', [AdminController::class, 'count_student']);
        Route::get('count-teacher', [AdminController::class, 'count_teacher']);
        Route::delete('delete-teacher/{id}', [AdminController::class, 'destroy_teacher']);
        Route::delete('delete-student/{id}', [AdminController::class, 'destroy_student']);
    });
    Route::group(['prefix' => 'block-list', 'middleware' => ['hasRole:admin']], function () {
        Route::get('get', [BlockController::class, 'index']);
        Route::post('store/{id}', [BlockController::class, 'store']);
        Route::delete('unblock-user/{id}', [BlockController::class, 'destroy']);
    });

    //khader

    // Route::group(['middleware' => 'hasRole:admin'], function () {
    Route::group(['prefix' => 'QualificationCourse'], function () {
        Route::post('store', [QualificationCourseController::class, 'store']);
        Route::post('update/{id}', [QualificationCourseController::class, 'update']);
        Route::delete('delete/{id}', [QualificationCourseController::class, 'destroy']);
        Route::get('getall', [QualificationCourseController::class, 'index']);
        Route::get('getById/{id}', [QualificationCourseController::class, 'show']);
        // });
    });

    Route::group(['middleware' => 'hasRole:teacher'], function () {
        Route::group(['prefix' => 'QualificationCourse'], function () {
            Route::get('getall', [QualificationCourseController::class, 'index']);
            Route::post('insert_into_courses/{id}', [QualificationCourseController::class, 'insert_into_courses']);
            Route::get('show_my_courses', [QualificationCourseController::class, 'show_my_courses'])
                ->middleware('hasRole:teacher');
        });
    });

    Route::group(['prefix' => 'calender'], function () {
        Route::group(['middleware' => ['hasRole:teacher']], function () {
            Route::post('store', [CalendarController::class, 'store']);
            Route::get('get', [CalendarController::class, 'index']);
            Route::get('accept-request/{id}', [LockHourController::class, 'accept_request']);
            Route::get('user_lock', [LockHourController::class, 'index']);
            Route::delete('delete/{id}', [LockHourController::class, 'destroy']);
        });
        Route::group(['middleware' => 'hasRole:student'], function () {
            Route::get('getById/{id}', [CalendarController::class, 'show']);
            Route::post('lock-hour', [LockHourController::class, 'store']);
            Route::get('delete-request/{id}', [LockHourController::class, 'delete_my_request']);
            Route::get('show_my_request', [LockHourController::class, 'get_my_request']);
        });
    });

    Route::controller(CompleteStudentController::class)
        ->prefix('complete-student')->middleware('hasRole:student')->group(function () {
            Route::get('get', 'index');
            Route::post('store', 'store');
            Route::post('update', 'update');
        });

    Route::group(['middleware' => ['hasRole:student']], function () {
        Route::group(['prefix' => 'ProfileStudentAds'], function () {
            Route::post('store', [ProfileStudentAdsController::class, 'store'])->middleware('profileStudent');
            Route::delete('delete/{id}', [ProfileStudentAdsController::class, 'destroy']);
            Route::get('getMyAds', [ProfileStudentAdsController::class, 'getMyAds']);
            Route::get('getById/{id}', [ProfileStudentAdsController::class, 'show']);
        });
    });
    Route::group(['prefix' => 'notifications'], function () {
        Route::get("", [NotificationController::class, 'getAll']);
        Route::get("not_viewed", [NotificationController::class, 'getNotificationsNotViewed']);
        Route::get("viewed", [NotificationController::class, 'getNotificationsViewed']);
        Route::get("{id}", [NotificationController::class, 'getById']);
        Route::delete("{id}", [NotificationController::class, 'delete']);
    });
});

