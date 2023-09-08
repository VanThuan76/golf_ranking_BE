<?php

namespace App\Traits;

use App\Admin\Controllers\UtilsCommonHelper;
use App\Http\Models\Member;
use App\Http\Models\Organiser;
use App\Http\Models\TournamentGroup;
use App\Http\Models\TournamentType;

trait TournamentFormattingTrait
{
    use MemberFormattingTrait, OrganiserFormattingTrait, TournamentGroupFormattingTrait, TournamentTypeFormattingTrait, TournamentSummaryFormattingTrait;
    private function _formatTournament($tournament, UtilsCommonHelper $commonController)
    {
        $tournamentTypes = TournamentType::all()->keyBy('id');
        if ($tournamentTypeRecord = $tournamentTypes->get($tournament->tournament_type_id)) {
            $tournament->tournament_type = $tournamentTypeRecord;
            $this->_formatTournamentType($tournamentTypeRecord, $commonController);
        } else {
            $tournament->tournament_type = null;
        }
        $tournamentGroups = TournamentGroup::all()->keyBy('id');
        if ($tournamentGroupRecord = $tournamentGroups->get($tournament->tournament_group_id)) {
            $tournament->tournament_group = $tournamentGroupRecord;
            $this->_formatTournamentGroup($tournamentGroupRecord, $commonController);
        } else {
            $tournament->tournament_group = null;
        }
        $members = Member::all()->keyBy('id');
        if ($memberRecord = $members->get($tournament->member_id)) {
            $tournament->member = $memberRecord;
            $this->_formatMember($memberRecord, $commonController);
        } else {
            $tournament->member = null;
        }
        $organisers = Organiser::all()->keyBy('id');
        if ($organiserRecord = $organisers->get($tournament->organiser_id)) {
            $tournament->organiser = $organiserRecord;
            $this->_formatOrganiser($organiserRecord, $commonController);
        } else {
            $tournament->organiser = null;
        }

        $tournament->region = $commonController->commonCodeGridFormatter('Region', 'description_vi', $tournament->region);
        $tournament->format = $commonController->commonCodeGridFormatter('Format', 'description_vi',  $tournament->format);
        $tournament->status = $commonController->commonCodeGridFormatter('TournamentStatus', 'description_vi',  $tournament->status);
        return $tournament;
    }
}
