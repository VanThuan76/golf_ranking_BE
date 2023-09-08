<?php

namespace App\Traits;

use App\Admin\Controllers\UtilsCommonHelper;
use App\Http\Models\Member;
use App\Http\Models\Tournament;
use App\Http\Models\TournamentSummary;

trait TournamentDetailFormattingTrait
{
    use TournamentFormattingTrait;
    private function _formatTournamentDetail($tournamentDetail,  UtilsCommonHelper $commonController)
    {
        $tournamentSummary = TournamentSummary::all()->keyBy('id');
        if ($tournamentSummaryRecord = $tournamentSummary->get($tournamentDetail->tournament_id)) {
            $tournamentDetail->tournament_summary = $tournamentSummaryRecord;
            $tournamentSummaryRecord->status = $commonController->commonCodeGridFormatter('TournamentStatus', 'description_vi',  $tournamentSummaryRecord->status);
        } else {
            $tournamentDetail->tournament_summary = null;
        }

        $tournaments = Tournament::all()->keyBy('id');
        if ($tournamentRecord = $tournaments->get($tournamentDetail->tournament_id)) {
            $tournamentDetail->tournament = $tournamentRecord;
            $this->_formatTournament($tournamentRecord, $commonController);
        } else {
            $tournamentDetail->tournament = null;
        }

        $members = Member::all()->keyBy('id');
        if ($memberRecord = $members->get($tournamentDetail->member_id)) {
            $tournamentDetail->member = $memberRecord;
            $this->_formatMember($memberRecord, $commonController);
        } else {
            $tournamentDetail->member = null;
        }

        return $tournamentDetail;
    }
}
