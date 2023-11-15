<?php

namespace App\Traits;

use App\Admin\Controllers\UtilsCommonHelper;
use App\Http\Models\Member;
use App\Http\Models\Tournament;
use App\Http\Models\TournamentSummary;

trait TournamentDetailFormattingTrait
{
    use TournamentFormattingTrait;
    private function _formatTournamentDetail($membersMap = null, $tournamentDetail, $tournamentId, UtilsCommonHelper $commonController)
    {
        //Tournament
        $tournaments = Tournament::all()->keyBy('id');
        if ($tournamentRecord = $tournaments->get($tournamentId ?? $tournamentDetail->tournament_id)) {
            $tournamentDetail->tournament = $tournamentRecord;
            $this->_formatTournament($tournamentRecord, $commonController);
        } else {
            $tournamentDetail->tournament = null;
        }

        //Member && TournamentSummary
        $tournamentSummary = TournamentSummary::all()->keyBy('member_id');;
        if ($membersMap !== null) {
            $members = $membersMap;
        } else {
            $members = Member::query()->get()->keyBy('id');
        }
        if ($memberRecord = $members->get($tournamentDetail->member_id)) {
            $tournamentDetail->member = $memberRecord;
            $tournamentSummary = $tournamentSummary->get($memberRecord->member_id);
            $tournamentDetail->tournament_summary = $tournamentSummary;
            $this->_formatMember($memberRecord, $commonController);
        } else {
            $tournamentDetail->member = null;
        }

        return $tournamentDetail;
    }
}
