<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    protected $table = 'register';

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
	protected $hidden = [
    ];

	protected $guarded = [];
}
