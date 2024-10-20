<?php

namespace App\Exports;

use App\Models\Dosen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class DosenExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles, WithDrawings, WithEvents
{
    private $dosens;

    public function __construct()
    {
        $this->dosens = Dosen::all(); // Fetch the Dosen data
    }

    public function collection()
    {
        return $this->dosens;
    }

    // Defining column headings
    public function headings(): array
    {
        return [
            'No', 'NIDN', 'Nama Dosen', 'Tanggal Mulai Tugas', 'Jenjang Pendidikan', 'Bidang Keilmuan', 'Foto Dosen'
        ];
    }

    // Mapping each row of data
    public function map($dosen): array
    {
        return [
            $dosen->id, // Change this to $loop->iteration if looping in controller
            $dosen->nidn,
            $dosen->nama_dosen,
            $dosen->tgl_mulai_tugas,
            $dosen->jenjang_pendidikan,
            $dosen->bidang_keilmuan,
            '' // Placeholder for image; will be replaced with drawings()
        ];
    }

    // Set the column widths
    public function columnWidths(): array
    {
        return [
            'A' => 5,  // No
            'B' => 15, // NIDN
            'C' => 30, // Nama Dosen
            'D' => 20, // Tanggal Mulai Tugas
            'E' => 15, // Jenjang Pendidikan
            'F' => 25, // Bidang Keilmuan
            'G' => 20, // Foto Dosen
        ];
    }

    // Apply styles to the worksheet
    public function styles(Worksheet $sheet)
    {
        // Set borders and alignments for the data cells
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G100')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle('A1:F100')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:F100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    // Add the images to the cells
    public function drawings()
    {
        $drawings = [];

        foreach ($this->dosens as $index => $dosen) {
            if ($dosen->foto_dosen) {
                $drawing = new Drawing();
                $drawing->setName('Foto Dosen');
                $drawing->setDescription('Dosen Image');
                $drawing->setPath(storage_path('app/public/' . $dosen->foto_dosen)); // Ensure the path is correct
                $drawing->setHeight(50); // Set image height, you can adjust as needed
                $drawing->setCoordinates('G' . ($index + 2)); // Offset by 2 for header
                $drawing->setOffsetX(10); // Horizontal padding for the image
                $drawing->setOffsetY(10); // Vertical padding for the image
                $drawings[] = $drawing;
            }
        }

        return $drawings;
    }

    // Adjust row height after sheet is generated
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Set height for each row
                foreach (range(2, $this->dosens->count() + 1) as $row) {
                    $event->sheet->getDelegate()->getRowDimension($row)->setRowHeight(60); // Adjust height for rows with images
                }
            },
        ];
    }
}
