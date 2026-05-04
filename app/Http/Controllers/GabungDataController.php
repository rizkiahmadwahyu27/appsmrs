<?php

namespace App\Http\Controllers;

use App\Exports\PasienExport;
use App\Imports\GabungDataImport;
use App\Imports\UpdateDataGabungImport;
use App\Models\GabungData;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GabungDataController extends Controller
{
    public function gabung_data(){
        return view('gabung_data');
    }

    public function ambilPasien(Request $request)
    {
        $query = GabungData::query();

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('masuk', [
                $request->from_date,
                $request->to_date
            ]);
        }

        return response()->json([
            'data' => $query->get()
        ]);
    }

    public function exportPasien(Request $request)
    {
        return Excel::download(
            new PasienExport($request->from_date, $request->to_date),
            'data_pasien.xlsx'
        );
    }

    public function import_gabung(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new GabungDataImport, $request->file('file'));

        return back()->with('success', 'Data pasien berhasil diimport');
    }
    public function update_import_gabung(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new UpdateDataGabungImport, $request->file('file'));

        return back()->with('success', 'Data pasien berhasil diimport');
    }
}
