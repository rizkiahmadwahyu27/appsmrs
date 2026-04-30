<?php

namespace App\Imports;

use App\Models\GabungData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GabungDataImport implements ToModel, WithHeadingRow, WithMapping, SkipsEmptyRows
{
    /**
     * 🔧 Helper: Bersihkan value
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

    /**
     * 🔧 Helper: Parse tanggal
     */
    private function parseDate($value)
    {
        $value = $this->clean($value);

        if (!$value) return null;

        try {
            if (is_numeric($value)) {
                return Carbon::instance(
                    ExcelDate::excelToDateTimeObject($value)
                );
            }

            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * 🔧 Mapping Excel
     */
    public function map($row): array
    {
        $rm     = $this->clean($row['rm'] ?? null);
        $nama   = $this->clean($row['nama'] ?? null);
        $sep    = $this->clean($row['sep'] ?? null);
        $kelas  = $this->clean($row['kelas'] ?? null);
        $naik   = $this->clean($row['naik'] ?? null);
        $ruang  = $this->clean($row['ruang'] ?? null);
        $dpjp   = $this->clean($row['dpjp'] ?? null);
        $kamar  = $this->clean($row['kamar'] ?? null);

        // ===== MASUK =====
        $masuk = $this->parseDate($row['masuk'] ?? null);
        $jam_masuk = $this->parseDate($row['jam_masuk'] ?? null);

        if ($masuk && $jam_masuk) {
            $masuk->setTime(
                $jam_masuk->hour,
                $jam_masuk->minute,
                $jam_masuk->second
            );
        }

        // ===== KELUAR =====
        $keluar = $this->parseDate($row['keluar'] ?? null);
        $jam_keluar = $this->parseDate($row['jam_keluar'] ?? null);

        if ($keluar && $jam_keluar) {
            $keluar->setTime(
                $jam_keluar->hour,
                $jam_keluar->minute,
                $jam_keluar->second
            );
        }

        return [
            'rm'     => is_numeric($rm) ? (int) $rm : null,
            'nama'   => $nama,
            'sep'    => $sep,
            'kelas'  => $kelas,
            'naik'   => $naik,
            'ruang'  => $ruang,
            'masuk'  => $masuk,
            'keluar' => $keluar,
            'dpjp'   => $dpjp,
            'kamar'  => $kamar,
        ];
    }

    /**
     * 🚀 Insert data (TIDAK UPDATE)
     */
    public function model(array $row)
    {
        // 🚨 Skip kalau RM kosong
        if (empty($row['rm'])) {
            Log::warning('Skip row tanpa RM', $row);
            return null;
        }

        $masuk  = $row['masuk'];
        $keluar = $row['keluar'];
        $kamar  = $row['kamar'];

        $keterangan = '';

        /**
         * 🔍 CEK BENTROK
         */
        if ($masuk && $keluar && $kamar) {
            $bentrok = GabungData::where('kamar', $kamar)
                ->where(function ($query) use ($masuk, $keluar) {
                    $query->whereBetween('masuk', [$masuk, $keluar])
                        ->orWhereBetween('keluar', [$masuk, $keluar])
                        ->orWhere(function ($q) use ($masuk, $keluar) {
                            $q->where('masuk', '<=', $masuk)
                              ->where('keluar', '>=', $keluar);
                        });
                })
                ->get();

            if ($bentrok->count() > 0) {
                $nama_pasien = $bentrok->pluck('nama')->join(', ');
                $keterangan .= "Data Bentrok dengan Pasien $nama_pasien";
            }
        }

        /**
         * 🔍 CEK STERIL (pasien sebelumnya)
         */
        if ($masuk && $kamar) {
            $sebelumnya = GabungData::where('kamar', $kamar)
                ->whereNotNull('keluar')
                ->where('keluar', '<=', $masuk)
                ->orderBy('keluar', 'desc')
                ->first();

            if ($sebelumnya) {
                $selisihMenit = Carbon::parse($sebelumnya->keluar)
                    ->diffInMinutes($masuk);

                if ($selisihMenit < 30) {
                    $keterangan .= ($keterangan ? ' | ' : '') . 'Ruangan masih belum steril';
                }
            }
        }

        /**
         * 🚀 INSERT DATA (TIDAK PAKE updateOrCreate)
         */
        return new GabungData([
            'rm'     => $row['rm'],
            'nama'   => $row['nama'],
            'sep'    => $row['sep'],
            'kelas'  => $row['kelas'],
            'naik'   => $row['naik'],
            'ruang'  => $row['ruang'],
            'masuk'  => $masuk,
            'keluar' => $keluar,
            'ket'    => $keterangan ?: null,
            'dpjp'   => $row['dpjp'],
            'kamar'  => $kamar,
        ]);
    }
}