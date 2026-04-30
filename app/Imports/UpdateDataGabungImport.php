<?php

namespace App\Imports;

use App\Models\GabungData;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class UpdateDataGabungImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
     /**
     * 🔧 Helper bersihkan data
     */
    private function clean($value)
    {
        if ($value === null) return null;

        $value = trim($value);

        if ($value === '' || strtolower($value) === 'null') {
            return null;
        }

        return $value;
    }

    public function model(array $row)
    {
        $rm    = $this->clean($row['rm'] ?? null);
        $sep   = $this->clean($row['sep'] ?? null);
        $kelas = $this->clean($row['kelas'] ?? null);
        $naik  = $this->clean($row['naik'] ?? null);

        if (!$rm) {
            return null;
        }

        $update = [];

        if ($sep !== null) {
            $update['sep'] = $sep;
        }

        if ($kelas !== null) {
            $update['kelas'] = $kelas;
        }

        if ($naik !== null) {
            $update['naik'] = $naik;
        }

        if (!empty($update)) {
            GabungData::where('rm', $rm)->update($update);
        }

        return null;
    }
}
