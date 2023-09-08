<?php

namespace App\Traits;

use App\Admin\Controllers\UtilsCommonHelper;
trait TournamentTypeFormattingTrait
{
    private function _formatTournamentType($tournamentType, UtilsCommonHelper $commonController)
    {
        $tournamentType->status = $commonController->commonCodeGridFormatter('Status', 'description_vi', $tournamentType->status);
        return $tournamentType;
    }
}
