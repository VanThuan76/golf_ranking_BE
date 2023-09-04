<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'member';

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
	protected $hidden = [
    ];

	protected $guarded = [];
}
