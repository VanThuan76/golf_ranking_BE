<?php

namespace App\Http\Controllers;

use App\Http\Models\Tournament;
use App\Admin\Controllers\UtilsCommonHelper;
use App\Http\Models\Organiser;
use App\Http\Models\TournamentGroup;
use App\Http\Models\TournamentType;
use App\Traits\ResponseFormattingTrait;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    use ResponseFormattingTrait;

    private function transformTournamentData($tournaments, UtilsCommonHelper $commonController)
    {
        return $tournaments->map(function ($tournament) use ($commonController) {
            $tournamentTypes = TournamentType::all()->keyBy('id');
            if ($tournamentType = $tournamentTypes->get($tournament->tournament_type_id)) {
                $tournament->tournament_type = $tournamentType;
            } else {
                $tournament->tournament_type = null;
            }
            $tournamentGroups = TournamentGroup::all()->keyBy('id');
            if ($tournamentGroup = $tournamentGroups->get($tournament->tournament_group_id)) {
                $tournament->tournament_group = $tournamentGroup;
            } else {
                $tournament->tournament_group = null;
            }
            $organisers = Organiser::all()->keyBy('id');
            if ($organiser = $organisers->get($tournament->organiser_id)) {
                $tournament->organiser = $organiser;
            } else {
                $tournament->organiser = null;
            }
            $tournament->region = $commonController->commonCodeGridFormatter('Region', 'description_vi', $tournament->region);
            $tournament->format = $commonController->commonCodeGridFormatter('Format', 'description_vi',  $tournament->format);
            $tournament->status = $commonController->commonCodeGridFormatter('TournamentStatus', 'description_vi',  $tournament->status);
            return $tournament;
        });
    }

    public function getList(Request $request, UtilsCommonHelper $commonController)
    {
        $page = $request->input('page', 1);
        $size = $request->input('size', 10);
        $sorts = $request->input('sorts', []);

        $tournaments = Tournament::orderBy($sorts[0]['field'], $sorts[0]['direction'])
            ->paginate($size, ['*'], 'page', $page);
        $transformedTournaments = $this->transformTournamentData($tournaments->getCollection(), $commonController);


        return response()->json($this->_formatCountResponse(
            $transformedTournaments,
            $tournaments->perPage(),
            $tournaments->total()
        ));
    }

    public function getById($id, UtilsCommonHelper $commonController)
    {
        $tournament = Tournament::find($id);

        if (!$tournament) {
            $response = $this->_formatBaseResponse(404, null, 'Giải đấu không được tìm thấy');
            return response()->json($response, 404);
        }

        $transformedTournament = $this->transformTournamentData(collect([$tournament]), $commonController)->first();

        $response = $this->_formatBaseResponse(200, $transformedTournament, 'Lấy dữ liệu thành công');
        return response()->json($response);
    }
}
