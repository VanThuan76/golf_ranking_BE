<?php

namespace App\Http\Controllers;

use App\Admin\Controllers\UtilsCommonHelper;
use App\Http\Models\TournamentSummary;
use App\Traits\MemberFormattingTrait;
use App\Traits\ResponseFormattingTrait;
use App\Traits\TournamentFormattingTrait;
use App\Traits\TournamentSummaryFormattingTrait;
use Illuminate\Http\Request;

class TournamentSummaryController extends Controller
{
    use ResponseFormattingTrait, TournamentFormattingTrait, MemberFormattingTrait, TournamentSummaryFormattingTrait;

    public function getList(Request $request, UtilsCommonHelper $commonController)
    {
        $page = $request->input('page', 1);
        $size = $request->input('size', 10);
        $sorts = $request->input('sorts', []);
        $memberId = $request->input('member_id');
        $query = TournamentSummary::orderBy($sorts[0]['field'], $sorts[0]['direction']);
        if (!empty($memberId)) {
            $query->where('member_id', $memberId);
        }
        $tournamentSummary = $query->paginate($size);
        $transformedTournamentSummary = $this->_formatTournamentSummary($tournamentSummary->getCollection(), $commonController);
        $totalPages = $tournamentSummary->lastPage();

        return response()->json($this->_formatCountResponse(
            $transformedTournamentSummary,
            $tournamentSummary->perPage(),
            $totalPages
        ));
    }
}
