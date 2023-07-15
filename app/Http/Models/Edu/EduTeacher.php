<?php

namespace App\Http\Models\Edu;

use App\Http\Models\Core\Business;
use Illuminate\Database\Eloquent\Model;

class EduTeacher extends Model
{
    protected $table = 'edu_teacher';

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
	protected $hidden = [
    ];

	protected $guarded = [];
}
