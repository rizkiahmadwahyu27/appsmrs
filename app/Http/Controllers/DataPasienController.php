<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Imports\DataPasienImport;
use App\Models\DataPasien;
use Maatwebsite\Excel\Facades\Excel;

class DataPasienController extends Controller
{
    public function data_pasien(){
        return view('data_pasien');
    }
    public function getData()
    {
        return response()->json([
            'data' => DataPasien::all()
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new DataPasienImport, $request->file('file'));

        return back()->with('success', 'Data pasien berhasil diimport');
    }
}
