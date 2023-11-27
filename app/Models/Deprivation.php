<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenAdmin\Admin\Auth\Database\Administrator;

class Deprivation extends Model
{
    public function student()
    {
        return $this->belongsTo(Administrator::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Administrator::class, 'lifted_by');
    }
}
