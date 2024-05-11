<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',//
        'price',//
        'description',//
        'file',//
        'number_students',//
        'profile_teacher_id',
        'status',
        'place',//
        'date',//
        //'offer'
    ];

    public function profile_teacher()
    {
        return $this->belongsTo(ProfileTeacher::class, 'profile_teacher_id');
    }
}
