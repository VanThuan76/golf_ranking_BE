<?php

namespace App\Traits;

use App\Admin\Controllers\UtilsCommonHelper;
use App\Http\Models\Member;
use App\Http\Models\Tournament;
trait TournamentSummaryFormattingTrait
{
    private function _formatTournamentSummary($tournamentSummary, UtilsCommonHelper $commonController)
    {
        $transformedTournaments = [];

        foreach ($tournamentSummary as $tournament) {
            $tournamentId = $tournament->tournament_id;
            $memberId = $tournament->member_id;

            $tournamentRecord = Tournament::find($tournamentId);
            $memberRecord = Member::find($memberId);

            if ($tournamentRecord) {
                $tournament->tournament = $tournamentRecord;
                $this->_formatTournament($tournamentRecord, $commonController);
            } else {
                $tournament->tournament = null;
            }

            if ($memberRecord) {
                $tournament->member= $memberRecord;
                $this->_formatMember($memberRecord, $commonController);
            } else {
                $tournament->member = null;
            }
            $transformedTournaments[] = $tournament;
        }

        $tournament->status = $commonController->commonCodeGridFormatter('TournamentStatus', 'description_vi',  $tournament->status);
        return $transformedTournaments;
    }
}
