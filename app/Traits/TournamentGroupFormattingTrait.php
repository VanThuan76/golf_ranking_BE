<?php

namespace App\Traits;

use App\Admin\Controllers\UtilsCommonHelper;
trait TournamentGroupFormattingTrait
{
    private function _formatTournamentGroup($tournamentGroup, UtilsCommonHelper $commonController)
    {
        $tournamentGroup->status = $commonController->commonCodeGridFormatter('Status', 'description_vi', $tournamentGroup->status);
        return $tournamentGroup;
    }
}
