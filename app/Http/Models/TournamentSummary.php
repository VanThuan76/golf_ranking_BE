<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentSummary extends Model
{
    protected $table = 'tournament_summary';

    public function tournament()
    {
        return $this->belongsTo(Tournament::class, 'tournament_id');
    }
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
	protected $hidden = ['tournament_id', 'member_id'];

	protected $guarded = [];
}
