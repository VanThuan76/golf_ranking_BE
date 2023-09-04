<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentType extends Model
{
    protected $table = 'tournament_type';

    public function tournamentGroup()
    {
        return $this->belongsTo(TournamentGroup::class, 'tournament_group_id');
    }
	protected $hidden = ['tournament_group_id'];

	protected $guarded = [];
}
