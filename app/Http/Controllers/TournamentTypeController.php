<?php

namespace App\Http\Controllers;

use App\Admin\Controllers\UtilsCommonHelper;
use App\Http\Models\TournamentType;
use App\Traits\ResponseFormattingTrait;

class TournamentTypeController extends Controller
{
    use ResponseFormattingTrait;
    public function getList(UtilsCommonHelper $commonController)
    {
        $tournaments = TournamentType::all();

        $transformedTournaments = $tournaments->map(function ($tournaments) use ($commonController) {
            $tournaments->status = $commonController->commonCodeGridFormatter('Status', 'description_vi', $tournaments->status);
            return $tournaments;
        });

        $response = $this->_formatBaseResponse(200, $transformedTournaments, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }
}
