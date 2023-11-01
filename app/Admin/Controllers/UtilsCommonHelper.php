<?php

namespace App\Admin\Controllers;

use App\Http\Models\CommonCode;
use App\Http\Models\Member;
use App\Http\Models\Organiser;
use App\Http\Models\Tournament;
use App\Http\Models\TournamentGroup;
use App\Http\Models\TournamentType;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class UtilsCommonHelper
{
    public static function dateFormatter($date)
    {
        $carbonDate = Carbon::parse($date)->timezone(Config::get('app.timezone'));
        return $carbonDate->format('d/m/Y');
    }
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
            if($result == 1){
                return "<span class='label label-success'>$commonCode->description_vi</span>";
            }else if($result == 2){
                return "<span class='label label-warning'>$commonCode->description_vi</span>";
            }else{
                return "<span class='label label-danger'>$commonCode->description_vi</span>";
            }
        }
        return $commonCode->description_vi;
    }
    public static function statusFormFormatter()
    {
        return self::commonCode("status", "description_vi", "value");
    }
    public static function statusCustomizeFormFormatter($statusCustomize)
    {
        return self::commonCode($statusCustomize, "description_vi", "value");
    }
    public static function statusGridFormatter($status)
    {
        return self::statusFormatter($status, "grid");
    }
    public static function statusDetailFormatter($status)
    {
        return self::statusFormatter($status, "Core", "detail");
    }
    public static function optionsTournament()
    {
        return Tournament::where('status', 1)->pluck('name', 'id');
    }
    public static function optionsMember()
    {
        return Member::where('status', 1)->pluck('name', 'id');
    }
    public static function optionsTournamentType()
    {
        return TournamentType::where('status', 1)->pluck('name', 'id');
    }
    public static function optionsTournamentGroup()
    {
        return TournamentGroup::where('status', 1)->pluck('name', 'id');
    }
    public static function optionsOrganiser()
    {
        return Organiser::where('status', 1)->pluck('name', 'id');
    }
    static function extractContent($title)
    {
        return strlen($title) < 30 ? $title : (substr($title, 0, 30) . "...");
    }
    static function createSlug($title, $allSlugs)
    {
        $slug = Str::slug($title);

        if (!$allSlugs->contains('slug', $slug)) {
            return $slug;
        }

        $i = 0;
        do {
            $i = $i + 1;
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        } while (true);
    }
}
