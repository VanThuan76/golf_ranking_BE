<?php

namespace App\Traits;

use App\Admin\Controllers\UtilsCommonHelper;
trait OrganiserFormattingTrait
{
    private function _formatOrganiser($organiser, UtilsCommonHelper $commonController)
    {
        $organiser->status = $commonController->commonCodeGridFormatter('Status', 'description_vi', $organiser->status);
        return $organiser;
    }
}
