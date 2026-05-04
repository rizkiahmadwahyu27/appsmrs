<?php

namespace App\Exports;

use App\Models\GabungData;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; // 🔥 TAMBAH INI

class PasienExport implements FromCollection, WithHeadings // 🔥 TAMBAH DI SINI
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        $query = GabungData::query();

        if ($this->from && $this->to) {
            $query->whereBetween('masuk', [
                $this->from,
                $this->to
            ]);
        }

        return $query->get([
            'rm',
            'nama',
            'sep',
            'kelas',
            'naik',
            'masuk',
            'keluar',
            'ruang',
            'kamar',
            'dpjp',
            'ket'
        ]);
    }

    public function headings(): array
    {
        return [
            'RM',
            'Nama',
            'SEP',
            'Kelas',
            'Naik',
            'Masuk',
            'Keluar',
            'Ruang',
            'Kamar',
            'DPJP',
            'Keterangan'
        ];
    }
}