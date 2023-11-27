<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenAdmin\Admin\Auth\Database\Administrator;


class SemesterApologyExcuse extends Model
{
    protected $table = 'semester_apology_excuses';

    public function student()
    {
        return $this->belongsTo(Administrator::class);
    }

    
}
