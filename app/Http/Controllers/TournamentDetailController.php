<?php

namespace App\Http\Controllers;

use App\Admin\Controllers\UtilsCommonHelper;
use App\Http\Models\TournamentDetail;
use App\Traits\ResponseFormattingTrait;
use App\Traits\TournamentDetailFormattingTrait;
use Illuminate\Http\Request;

class TournamentDetailController extends Controller
{
    use ResponseFormattingTrait, TournamentDetailFormattingTrait;
    public function getList(Request $request, UtilsCommonHelper $commonController)
    {
        $page = $request->input('page', 1);
        $size = $request->input('size', 10);
        $sorts = $request->input('sorts', []);
        $filters = $request->input('filters', []);

        $tournamentId = null;
        foreach ($filters as $filter) {
            if ($filter['field'] === 'tournament_id') {
                $tournamentId = $filter['value'];
                break;
            }
        }

        $tournamentDetails = TournamentDetail::where('tournament_id', $tournamentId)->orderBy($sorts[0]['field'], $sorts[0]['direction'])
            ->paginate($size, ['*'], 'page', $page);

        $transformedTournamentDetails = [];
        foreach ($tournamentDetails->getCollection() as $tournamentDetail) {
            $tournamentDetail = $this->_formatTournamentDetail($tournamentDetail, $tournamentId, $commonController);
            $transformedTournamentDetails[] = $tournamentDetail;
        }
        $totalPages = ceil($tournamentDetails->total() / $size);

        return response()->json($this->_formatCountResponse(
            $transformedTournamentDetails,
            $tournamentDetails->perPage(),
            $totalPages
        ));
    }
}
