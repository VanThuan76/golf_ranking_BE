<?php

namespace App\Traits;

use App\Admin\Controllers\UtilsCommonHelper;
use App\Http\Models\Group;

trait MemberFormattingTrait
{
    use GroupFormattingTrait;
    private function _formatMember($member, UtilsCommonHelper $commonController)
    {
        $groups = Group::all()->keyBy('id');
        if ($groupRecord = $groups->get($member->group_id)) {
            $member->group = $groupRecord;
            $this->_formatGroup($groupRecord, $commonController);
        } else {
            $member->group = null;
        }
        $member->gender = $commonController->commonCodeGridFormatter('Gender', 'description_vi', $member->gender);
        $member->status = $commonController->commonCodeGridFormatter('Status', 'description_vi', $member->status);
        return $member;
    }
}
