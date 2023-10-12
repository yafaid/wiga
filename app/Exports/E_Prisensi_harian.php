<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class E_Prisensi_harian implements FromCollection, WithHeadings, WithCustomStartCell, WithStyles, WithColumnWidths
{
    protected $data;
    protected $startDate;
    protected $endDate;

    public function __construct($data, $startDate, $endDate)
    {
        $this->data = $data;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $dates = $this->generateDateRange($this->startDate, $this->endDate);
        $formattedData = [];

        // Inisialisasi data kosong untuk setiap tanggal
        foreach ($dates as $date) {
            $formattedData[$date] = [
                'No' => '',
                'Nama' => '',
                'Kode Kelas' => '',
                'Total Kehadiran' => '',
            ];
        }

        // Mengisi data kehadiran berdasarkan tanggal
        foreach ($this->data as $item) {
            $formattedData[$item->tanggal]['No'] = ++$count; // Kolom hitungan di kolom A
            $formattedData[$item->tanggal]['Nama'] = $item->nama;
            $formattedData[$item->tanggal]['Kode Kelas'] = $item->kodekelas;
            $formattedData[$item->tanggal][$item->tanggal] = 'a'; // Mengisi kolom tanggal dengan "a"
            $formattedData[$item->tanggal]['Total Kehadiran'] = $item->total_kehadiran;
        }

        return collect($formattedData);
    }

    private function generateDateRange($startDate, $endDate)
    {
        $dates = [];
        $currentDate = $startDate;

        while ($currentDate <= $endDate) {
            $dates[] = $currentDate;
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }

        return $dates;
    }

    public function headings(): array
    {
        $dates = $this->generateDateRange($this->startDate, $this->endDate);
        $header = ['No', 'Nama', 'Kode Kelas'];

        foreach ($dates as $date) {
            $header[$date] = $date; // Tambahkan tanggal ke header
        }

        $header['Total Kehadiran'] = 'Total Kehadiran';

        return $header;
    }

    public function startCell(): string
    {
        return 'A1'; // Mulai dari sel A1
    }

    public function styles(Worksheet $sheet)
    {
        // Mengatur garis batas untuk seluruh sel dengan gaya border solid
        $sheet->getStyle($sheet->calculateWorksheetDimension())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        return [
            '1' => ['font' => ['bold' => true], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // Mengatur lebar kolom A menjadi 15
            'B' => 15, // Mengatur lebar kolom B menjadi 15
            'C' => 25, // Mengatur lebar kolom C menjadi 25
        ];
    }

    public function columnFormats(): array
    {
        $dateColumns = range('D', 'Z');
        $formats = [
            'A' => NumberFormat::FORMAT_NUMBER, // Format untuk kolom hitungan
            'B' => NumberFormat::FORMAT_TEXT,   // Format untuk kolom nama (teks)
            'C' => NumberFormat::FORMAT_TEXT,   // Format untuk kolom Kode Kelas (teks)
        ];

        foreach ($dateColumns as $column) {
            $formats[$column] = NumberFormat::FORMAT_TEXT; // Format untuk kolom tanggal
        }

        $formats['Z'] = NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1; // Format untuk kolom Total Kehadiran

        return $formats;
    }
}
