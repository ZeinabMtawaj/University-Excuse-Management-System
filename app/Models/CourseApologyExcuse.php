<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenAdmin\Admin\Auth\Database\Administrator;


class CourseApologyExcuse extends Model
{
    protected $table = 'course_apology_excuses';

    public function student()
    {
        return $this->belongsTo(Administrator::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

}
