<?php

namespace App\Imports;

use App\Http\Models\TournamentDetailDraft;
use Maatwebsite\Excel\Concerns\ToModel;

class TournamentDetailDraftImport implements ToModel
{
    public function model(array $row)
    {
        return new TournamentDetailDraft([
            'vjgr_code' => $row[0],
            'round_number' => $row[1],
            'score' => $row[2],
            'to_par' => $row[3],
        ]);
    }
}
