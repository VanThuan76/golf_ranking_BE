<?php

namespace App\Http\Controllers;

use App\Admin\Controllers\UtilsCommonHelper;
use App\Http\Models\TournamentDetail;
use App\Traits\ResponseFormattingTrait;
use App\Traits\TournamentDetailFormattingTrait;
use Illuminate\Http\Request;
use DB;

class TournamentDetailController extends Controller
{
    use ResponseFormattingTrait, TournamentDetailFormattingTrait;

    public function getList(Request $request, UtilsCommonHelper $commonController)
    {
        $filters = $request->input('filters', []);
        $size = $request->input('size', 10);
        $sorts = $request->input('sorts', []);
        $query = TournamentDetail::query();
        $queryDB = DB::table('vjgr.tournament_detail')->join('vjgr.member', 'vjgr.tournament_detail.member_id', '=', 'vjgr.member.id');
        $membersMap = null;
        $tournamentId = null;

        foreach ($filters as $filter) {
            // if (!empty($value)) {
            //     $query->where($field, 'like', '%' . $value . '%');
            // }
            $field = $filter['field'];
            $value = $filter['value'];
            if ($field === 'group_id') {
                $queryDB->where('vjgr.member.group_id', $field === 'group_id' ? $value : 1);
            }else if($field === 'tournament_id'){
                $queryDB->where('vjgr.tournament_detail.tournament_id',$field === 'tournament_id' ? $value : 1);
                $tournamentId = $field === 'tournament_id' ? $value : 1;
            }
        }
        $membersMap = $queryDB->get();

        foreach ($sorts as $sort) {
            $field = $sort['field'];
            $direction = $sort['direction'];

            if (!empty($field) && !empty($direction)) {
                $query->orderBy($field, $direction);
            }
        }

        $tournamentDetails = $query->paginate($size, ['*'], 'page', $request->input('page', 1));

        $transformedTournamentDetails = [];
        foreach ($tournamentDetails->getCollection() as $tournamentDetail) {
            $tournamentDetail = $this->_formatTournamentDetail($membersMap, $tournamentDetail, $tournamentId, $commonController);
            if ($tournamentDetail->member !== null) {
                $transformedTournamentDetails[] = $tournamentDetail;
            }
        }

        $totalPages = $tournamentDetails->lastPage();
        return response()->json(
            $this->_formatCountResponse(
                $transformedTournamentDetails,
                $tournamentDetails->perPage() - 1,
                $totalPages
            )
        );
    }
}
