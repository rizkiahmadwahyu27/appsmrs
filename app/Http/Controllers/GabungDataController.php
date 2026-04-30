<?php

namespace App\Http\Controllers;

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

    public function ambilPasien()
    {
        return response()->json([
            'data' => GabungData::all()
        ]);
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
