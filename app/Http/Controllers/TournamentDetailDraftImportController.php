<?php

namespace App\Http\Controllers;

use App\Http\Models\TournamentDetailDraft;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TournamentDetailDraftImportController extends Controller
{
    public function import(Request $request)
    {
        $file = $request->file('csv_file');

        $this->validate($request, [
            'csv_file' => 'required|mimes:csv'
        ]);

        try {
            Excel::import(new TournamentDetailDraft, $file);
            return redirect()->back()->with('success', 'Data Imported!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error importing data: ', $e->getMessage());
        }
    }
}