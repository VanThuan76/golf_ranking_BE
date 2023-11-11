<?php

namespace App\Traits;

use App\Admin\Controllers\UtilsCommonHelper;
use App\Http\Models\Group;

trait MemberFormattingTrait
{
    use GroupFormattingTrait;
    private function _formatMember($member, UtilsCommonHelper $commonController)
    {
        if ($member instanceof \Illuminate\Database\Eloquent\Collection) {
            // Xử lý trường hợp là collection
            return $member->map(function ($item) use ($commonController) {
                return $this->_formatMember($item, $commonController);
            });
        }
        $groups = Group::all()->keyBy('id');
        if ($member->group_id != null) {
            if ($groupRecord = $groups->get($member->group_id)) {
                $member->group = $groupRecord;
                $this->_formatGroup($groupRecord, $commonController);
            } else {
                $member->group = null;
            }
        }
        $member->gender = $commonController->commonCodeGridFormatter('Gender', 'description_vi', $member->gender);
        $member->status = $commonController->commonCodeGridFormatter('Status', 'description_vi', $member->status);
    
        return $member;
    }
    
}
