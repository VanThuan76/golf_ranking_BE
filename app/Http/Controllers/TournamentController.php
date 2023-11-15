<?php

namespace App\Http\Controllers;

use App\Http\Models\Tournament;
use App\Admin\Controllers\UtilsCommonHelper;
use App\Traits\ResponseFormattingTrait;
use App\Traits\TournamentFormattingTrait;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    use ResponseFormattingTrait, TournamentFormattingTrait;

    public function searchTournament(Request $request, UtilsCommonHelper $commonController)
    {
        $query = Tournament::query();
        $filters = $request->input('filters', []);

        foreach ($filters as $filter) {
            $field = $filter['field'];
            $value = $filter['value'];

            if (!empty($value)) {
                $query->where($field, 'like', '%' . $value . '%');
            }
        }

        $size = $request->input('size', 10);
        $sorts = $request->input('sorts', []);

        foreach ($sorts as $sort) {
            $field = $sort['field'];
            $direction = $sort['direction'];

            if (!empty($field) && !empty($direction)) {
                $query->orderBy($field, $direction);
            }
        }

        $tournaments = $query->paginate($size, ['*'], 'page', $request->input('page', 1));
        $transformedTournaments = [];
        foreach ($tournaments as $member) {
            $member = $this->_formatTournament($member, $commonController);
            $transformedTournaments[] = $member;
        }

        $totalPages = $tournaments->lastPage();
        return response()->json($this->_formatCountResponse(
            $transformedTournaments,
            $tournaments->perPage() - 1,
            $totalPages
        ));
    }


    public function getById($id, UtilsCommonHelper $commonController)
    {
        $tournament = Tournament::find($id);

        if (!$tournament) {
            $response = $this->_formatBaseResponse(404, null, 'Giải đấu không được tìm thấy');
            return response()->json($response, 404);
        }

        $transformedTournament = $this->_formatTournament($tournament, $commonController);

        $response = $this->_formatBaseResponse(200, $transformedTournament, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }
}
