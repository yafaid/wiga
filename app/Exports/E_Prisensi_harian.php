<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class E_Prisensi_harian implements FromCollection, WithHeadings, WithCustomStartCell, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return [
                'siswa_id' => $item->siswa_id,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama', 
        ];
    }

    public function startCell(): string
    {
        return 'B1'; // Mulai dari sel B1
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'B1' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]], 
        ];
    }
}

