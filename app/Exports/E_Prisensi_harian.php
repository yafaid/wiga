<?php

namespace App\Exports;
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

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $count = 0;
        $formattedData = [];
        $seenNames = [];

        // Mengelompokkan data berdasarkan tanggal
        foreach ($this->data as $item) {
            if (!in_array($item->nama, $seenNames)) {
                $seenNames[] = $item->nama; // Tandai nama sebagai "sudah muncul"
                $formattedData[$item->tanggal][] = [
                    'count' => ++$count, // Kolom hitungan di kolom A
                    'nama' => $item->nama,
                    'kodekelas' => $item->kodekelas,
                    'total_kehadiran' => $item->total_kehadiran,
                ];
            }
        }

      
    // Tambahkan data "a" di bawah header
    foreach ($formattedData as &$tanggalData) {
        foreach ($tanggalData as &$item) {
            foreach (array_keys($this->data->groupBy('tanggal')->toArray()) as $date) {
                if ($date !== 'total_kehadiran') {
                    $item[$date] = 'a'; // Mengisi semua kolom tanggal (selain 'total_kehadiran') dengan "a"
                }
            }
        }
    }

        return collect($formattedData);
    }

    public function headings(): array
    {
        $dates = array_keys($this->data->groupBy('tanggal')->toArray());
        $header = ['No', 'Nama', 'Kode Kelas']; // Kolom umum

        foreach ($dates as $date) {
            $header[] = $date; // Tambahkan tanggal ke header
        }

        $header[] = 'Total Kehadiran'; // Kolom total kehadiran

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
