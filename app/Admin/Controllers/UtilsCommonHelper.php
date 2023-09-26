<?php

namespace App\Admin\Controllers;

use App\Http\Models\CommonCode;
use App\Http\Models\TournamentGroup;
use App\Http\Models\TournamentType;
use Illuminate\Support\Str;

class UtilsCommonHelper
{
    public static function commonCode($type, $description, $value)
    {
        $commonCode = CommonCode::where('type', $type)
            ->pluck($description, $value);
        return $commonCode;
    }
    public static function commonCodeGridFormatter($type, $description, $value)
    {
        $commonCode = CommonCode::where('type', $type)
            ->where('value', $value)
            ->first();
        return $commonCode ? $commonCode->$description : '';
    }
    //Kiem tra ten lai(doi lai)
    public static function statusFormatter($value, $isGrid)
    {
        $result = $value ? $value : 0;
        $commonCode = CommonCode::where('type', 'Status')
            ->where('value', $result)
            ->first();
        if ($commonCode && $isGrid === "grid") {
            return $result === 1 ? "<span class='label label-success'>$commonCode->description_vi</span>" : "<span class='label label-danger'>$commonCode->description_vi</span>";
        }
        return $commonCode->description_vi;
    }
    public static function statusFormFormatter()
    {
        return self::commonCode("Core", "Status", "description_vi", "value");
    }
    public static function statusGridFormatter($status)
    {
        return self::statusFormatter($status, "Core", "grid");
    }
    public static function statusDetailFormatter($status)
    {
        return self::statusFormatter($status, "Core", "detail");
    }
    public static function optionsTournamentType()
    {
        return TournamentType::where('status', 1)->pluck('name', 'id');
    }
    public static function optionsTournamentGroup()
    {
        return TournamentGroup::where('status', 1)->pluck('name', 'id');
    }
    static function extractContent($title){
        return strlen($title) < 30 ? $title : (substr($title, 0, 30). "...");
    }
    static function createSlug($title, $allSlugs){
        $slug = Str::slug($title);

        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }

        $i = 0;
        do {
            $i = $i + 1;
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        } while (true);
    }
}
