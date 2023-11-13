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
        $query = TournamentDetail::query();

        $filters = $request->input('filters', []);

        foreach ($filters as $filter) {
            $field = $filter['field'];
            $value = $filter['value'];

            if (!empty($value)) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }
        $tournamentId = null;
        foreach ($filters as $filter) {
            if ($filter['field'] === 'tournament_id') {
                $tournamentId = $filter['value'];
                break;
            }
        }
        $page = $request->input('page', 1);
        $size = $request->input('size', 10);
        $sorts = $request->input('sorts', []);

        foreach ($sorts as $sort) {
            $field = $sort['field'];
            $direction = $sort['direction'];

            if (!empty($field) && !empty($direction)) {
                $query->orderBy($field, $direction);
            }
        }

        $tournamentDetails = $query->paginate($size);

        $transformedTournamentDetails = [];
        foreach ($tournamentDetails->getCollection() as $tournamentDetail) {
            $tournamentDetail = $this->_formatTournamentDetail($tournamentDetail, $tournamentId, $commonController);
            $transformedTournamentDetails[] = $tournamentDetail;
        }
        $totalPages = $tournamentDetails->lastPage();

        return response()->json($this->_formatCountResponse(
            $transformedTournamentDetails,
            $tournamentDetails->perPage() - 1,
            $totalPages
        )
        );
    }
}
