<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentDetailDraft extends Model
{
    protected $table = 'tournament_detail_draft';

    public function tournament()
    {
        return $this->belongsTo(Tournament::class, 'tournament_id');
    }
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
	protected $hidden = [
    ];

	protected $guarded = [];
}
