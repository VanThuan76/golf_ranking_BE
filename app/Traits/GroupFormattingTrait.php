<?php

namespace App\Traits;

use App\Admin\Controllers\UtilsCommonHelper;

trait GroupFormattingTrait
{
    private function _formatGroup($group, UtilsCommonHelper $commonController)
    {
        $group->gender = $commonController->commonCodeGridFormatter('Gender', 'description_vi', $group->gender);
        $group->status = $commonController->commonCodeGridFormatter('Status', 'description_vi', $group->status);
        return $group;
    }
}
