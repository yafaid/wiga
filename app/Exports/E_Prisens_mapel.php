<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class E_Prisens_mapel implements FromCollection
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        // Mendapatkan header dari tanggal yang di-foreach
        $headers = ['Nama Siswa'];
        foreach ($this->data as $tanggal => $kehadiran) {
            $headers[] = $tanggal;
        }

        return $headers;
    }
    public function columnWidths(): array
    {
        return [
            'A' => 15, // Mengatur lebar kolom A menjadi 15
            'B' => 15, // Mengatur lebar kolom B menjadi 15
            'C' => 25, // Mengatur lebar kolom C menjadi 25
            'D' => 25, // Mengatur lebar kolom D menjadi 25
            'E' => 25, // Mengatur lebar kolom E menjadi 25 (sesuaikan dengan kebutuhan Anda)
        ];
    }

    public function columnFormats(): array
    {
        $dateColumns = range('E', 'Z');
        $formats = [
            'A' => NumberFormat::FORMAT_NUMBER, // Format untuk kolom hitungan
            'B' => NumberFormat::FORMAT_TEXT,   // Format untuk kolom nama (teks)
        ];

        foreach ($dateColumns as $column) {
            $formats[$column] = NumberFormat::FORMAT_NUMBER; // Format untuk kolom tanggal
        }

        $formats['Z'] = NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1; // Format untuk kolom Total Kehadiran

        return $formats;
    }
}
