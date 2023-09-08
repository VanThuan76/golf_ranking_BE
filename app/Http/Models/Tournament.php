<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $table = 'tournament';

    public function tournamentGroup()
    {
        return $this->belongsTo(TournamentGroup::class, 'tournament_group_id');
    }
    public function tournamentType()
    {
        return $this->belongsTo(TournamentType::class, 'tournament_type_id');
    }
    public function organiser()
    {
        return $this->belongsTo(Organiser::class, 'organiser_id');
    }
    protected $hidden = ['tournament_type_id', 'tournament_group_id', 'organiser_id', 'member_id'];

	protected $guarded = [];
}
