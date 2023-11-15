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
        $tournamentSummary = TournamentSummary::all();

        $memberId = $tournamentDetail->member_id;
        $tournamentSummaryRecord = $tournamentSummary->where('member_id', $memberId)->where('tournament_id', $tournamentId)->first();

        if ($tournamentSummaryRecord) {
            $tournamentDetail->tournament_summary = $tournamentSummaryRecord;
            $tournamentSummaryRecord->status = $commonController->commonCodeGridFormatter('TournamentStatus', 'description_vi', $tournamentSummaryRecord->status);
        } else {
            $tournamentDetail->tournament_summary = null;
        }


        $tournaments = Tournament::all()->keyBy('id');
        if ($tournamentRecord = $tournaments->get($tournamentId ?? $tournamentDetail->tournament_id)) {
            $tournamentDetail->tournament = $tournamentRecord;
            $this->_formatTournament($tournamentRecord, $commonController);
        } else {
            $tournamentDetail->tournament = null;
        }
        if ($membersMap !== null) {
            $members = $membersMap;
        } else {
            $members = Member::query()->get()->keyBy('id');
        }

        if ($memberRecord = $members->get($tournamentDetail->member_id)) {
            $tournamentDetail->member = $memberRecord;
            $this->_formatMember($memberRecord, $commonController);
        } else {
            $tournamentDetail->member = null;
        }

        return $tournamentDetail;
    }
}
