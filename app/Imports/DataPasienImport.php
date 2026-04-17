<?php

namespace App\Imports;

use App\Models\DataPasien;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;

class DataPasienImport implements ToModel, WithHeadingRow, WithMapping
{
    public function map($row): array
    {
        // ===== TGL MASUK =====
        if (is_numeric($row['tgl_masuk'])) {
            $tgl_masuk = Carbon::instance(
                ExcelDate::excelToDateTimeObject($row['tgl_masuk'])
            );
        } else {
            $tgl_masuk = Carbon::parse($row['tgl_masuk']);
        }

        if (is_numeric($row['jam_masuk'])) {
            $jam_masuk = Carbon::instance(
                ExcelDate::excelToDateTimeObject($row['jam_masuk'])
            );
        } else {
            $jam_masuk = Carbon::parse($row['jam_masuk']);
        }

        $tgl_masuk->setTime(
            $jam_masuk->hour,
            $jam_masuk->minute,
            $jam_masuk->second
        );

        // ===== TGL KELUAR =====
        if (is_numeric($row['tgl_keluar'])) {
            $tgl_keluar = Carbon::instance(
                ExcelDate::excelToDateTimeObject($row['tgl_keluar'])
            );
        } else {
            $tgl_keluar = Carbon::parse($row['tgl_keluar']);
        }

        if (is_numeric($row['jam_keluar'])) {
            $jam_keluar = Carbon::instance(
                ExcelDate::excelToDateTimeObject($row['jam_keluar'])
            );
        } else {
            $jam_keluar = Carbon::parse($row['jam_keluar']);
        }

        $tgl_keluar->setTime(
            $jam_keluar->hour,
            $jam_keluar->minute,
            $jam_keluar->second
        );

        return [
            'rm'          => $row['rm'] ?? null,
            'nama'        => $row['nama'] ?? null,
            'no_sep'      => $row['no_sep'] ?? null,
            'kelas'       => $row['kelas'] ?? null,
            'naik_kelas'  => $row['naik_kelas'] ?? null,
            'status'      => $row['status'] ?? null,
            'tgl_masuk'   => $tgl_masuk,
            'tgl_keluar'  => $tgl_keluar,
            'dpjp'        => $row['dpjp'] ?? null,
            'ruangan'     => $row['ruangan'] ?? null,
            'keterangan'  => '',
        ];
    }

    public function model(array $row)
    {
        $tgl_masuk = $row['tgl_masuk'];
        $tgl_keluar = $row['tgl_keluar'];
        $ruangan = $row['ruangan'];

        $bentrok = DataPasien::where('ruangan', $ruangan)
            ->where(function ($query) use ($tgl_masuk, $tgl_keluar) {
                $query->whereBetween('tgl_masuk', [$tgl_masuk, $tgl_keluar])
                    ->orWhereBetween('tgl_keluar', [$tgl_masuk, $tgl_keluar])
                    ->orWhere(function ($q) use ($tgl_masuk, $tgl_keluar) {
                        $q->where('tgl_masuk', '<=', $tgl_masuk)
                          ->where('tgl_keluar', '>=', $tgl_keluar);
                    });
            })
            ->get();

        $keterangan = '';
        if ($bentrok->count() > 0) {
            $nama_pasien = $bentrok->pluck('nama')->join(', ');
            $keterangan = "Data Bentrok dengan Pasien $nama_pasien";
        }

        return new DataPasien([
            'rm'          => $row['rm'],
            'nama'        => $row['nama'],
            'no_sep'      => $row['no_sep'],
            'kelas'       => $row['kelas'],
            'naik_kelas'  => $row['naik_kelas'],
            'status'      => $row['status'],
            'tgl_masuk'   => $tgl_masuk,
            'tgl_keluar'  => $tgl_keluar,
            'dpjp'        => $row['dpjp'],
            'ruangan'     => $ruangan,
            'keterangan'  => $keterangan,
        ]);
    }
}