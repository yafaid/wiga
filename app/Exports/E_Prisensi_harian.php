<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class E_Prisensi_harian implements FromCollection, WithHeadings, WithCustomStartCell, WithStyles, WithColumnWidths
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
                'nama' => $item->nama,
                'kodekelas' => $item->kodekelas,
                'keterangan' => $item->keterangan,
                'tanggal' => \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y'), // Format tanggal ke "hari-bulan-tahun"
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama', 
            'Kode Kelas', 
            'Keterangan', 
            'Tanggal', 
        ];
    }

    public function startCell(): string
    {
        return 'B1'; // Mulai dari sel B1
    }

    public function styles(Worksheet $sheet)
    {
            // Mengatur garis batas untuk seluruh sel dengan gaya border solid
              // Mengatur garis batas untuk seluruh sel kecuali kolom A
        foreach ($sheet->getRowIterator() as $row) {
            foreach ($row->getCellIterator() as $cell) {
                if ($cell->getColumn() !== 'A') { // Selain kolom A
                    $cell->getStyle()->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }
            }
        }
        return [
            'B1' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]], 
            'C1' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]], 
            'D1' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]], 
            'E1' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]], 
        ];
    }
    public function columnWidths(): array
    {
        return [
            'B' => 25, // Mengatur lebar kolom C menjadi 15
            'C' => 25, // Mengatur lebar kolom C menjadi 15
            'D' => 25, // Mengatur lebar kolom C menjadi 15
            'E' => 25, // Mengatur lebar kolom C menjadi 15
        ];
    }
}

