<?php

namespace App\Exports;

use App\Models\Student;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class StudentsExport implements FromCollection, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{
    public function collection()
    {
        return Student::all();
    }

    public function map($student): array
    {
        return [
            $student['code'],
            $student['name'],
            $student['class'],
            $student['status'] == 1 ? 'Lunas' : 'Belum Lunas',
            $student['created_at']->translatedFormat('d F Y')
        ];
    }
    
    public function startCell(): string
    {
        return 'B8';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Tambahkan Kop Surat
                $sheet->mergeCells('B2:F2');
                $sheet->mergeCells('B3:F3');
                $sheet->mergeCells('B4:F4');
                $sheet->mergeCells('B5:F5');
                $sheet->mergeCells('B6:F6');

                $sheet->setCellValue('B3', 'LAPORAN DATA SISWA');
                $sheet->setCellValue('B4', 'BUSTANUL ATHFAL AISYIYAH 08 GROGOL');
                $sheet->setCellValue('B5', 'Mantung Rt.04 Rw. 05 Sanggrahan, Grogol, Sukoharjo');

                // Pindahkan header ke A6
                $sheet->setCellValue('B7', 'Kode Siswa');
                $sheet->setCellValue('C7', 'Nama Siswa');
                $sheet->setCellValue('D7', 'Kelas');
                $sheet->setCellValue('E7', 'Status');
                $sheet->setCellValue('F7', 'Tanggal Daftar');

                // Styling Kop Surat
                $sheet->getStyle('B2:F6')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    'borders' => [
                        'outline' => [ // Hanya border luar
                            'borderStyle' => Border::BORDER_THIN, // Bisa diganti BORDER_MEDIUM/BORDER_THICK
                            'color' => ['rgb' => '000000'], // Warna hitam
                        ],
                    ],
                ]);

                // Header
                $sheet->getStyle('B7:F7')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // body
                $lastRow = count(Student::get()) + 7;
                $sheet->getStyle("B7:F$lastRow")->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);


                // Footer
                $lastRow++;
                $footerRow = $lastRow;
                $sheet->setCellValue("B$footerRow", 'Mengesahkan,'); $footerRow++;
                $date = Carbon::now()->translatedFormat('d F Y');
                $sheet->setCellValue("B$footerRow", 'Grogol, ' . $date); $footerRow+=2;
                $sheet->setCellValue("B$footerRow", 'Kepala BA Aisyiyah 08 Grogol');
                $sheet->setCellValue("C$footerRow", 'Koordinator Pendidikan');

                $sheet->mergeCells("C$footerRow:D$footerRow");
                $sheet->getStyle("C$footerRow:D$footerRow")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->setCellValue("F$footerRow", 'Pembuat Laporan');
                $footerRow+=3;
                $sheet->setCellValue("B$footerRow", 'Sitti Istikomah, S.Pd.I');
                $sheet->setCellValue("C$footerRow", 'Miftahul Jannah, S.Pd');
                $sheet->mergeCells("C$footerRow:D$footerRow");
                $sheet->getStyle("C$footerRow:D$footerRow")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                
                $sheet->setCellValue("F$footerRow", 'Eni Nur Hayati, S.Pd.I');
                $footerRow++;

                $sheet->getStyle("B$lastRow:F$footerRow")->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
