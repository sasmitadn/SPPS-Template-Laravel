<?php

namespace App\Exports;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class InvoiceExport implements FromCollection, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Invoice::where('status', '!=', '-1')->get();
    }

    
    public function startCell(): string
    {
        return 'B8';
    }

    public function map($item): array
    {
        return [
            $item['title'],
            number_format($item['amount'], 0, ",", "."),
            $item['type'] == 1 ? 'Kredit' : 'One-Time Payment',
            $item['status'] == 1 ? 'Aktif' : 'Tidak Aktif'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Tambahkan Kop Surat
                $sheet->mergeCells('B2:E2');
                $sheet->mergeCells('B3:E3');
                $sheet->mergeCells('B4:E4');
                $sheet->mergeCells('B5:E5');
                $sheet->mergeCells('B6:E6');

                $sheet->setCellValue('B3', 'LAPORAN JENIS TAGIHAN');
                $sheet->setCellValue('B4', 'BUSTANUL ATHFAL AISYIYAH 08 GROGOL');
                $sheet->setCellValue('B5', 'Mantung Rt.04 Rw. 05 Sanggrahan, Grogol, Sukoharjo');

                // Pindahkan header ke A6
                $sheet->setCellValue('B7', 'Nama Tagihan');
                $sheet->setCellValue('C7', 'Jumlah');
                $sheet->setCellValue('D7', 'Jenis');
                $sheet->setCellValue('E7', 'Status');

                // Styling Kop Surat
                $sheet->getStyle('B2:E6')->applyFromArray([
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
                $sheet->getStyle('B7:E7')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // body
                $lastRow = count(Invoice::where('status', '!=', '-1')->get()) + 7;
                $sheet->getStyle("B7:E$lastRow")->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                $sheet->getStyle("C7:C$lastRow")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
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
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->setCellValue("E$footerRow", 'Pembuat Laporan');
                $footerRow+=3;
                $sheet->setCellValue("B$footerRow", 'Sitti Istikomah, S.Pd.I');
                $sheet->setCellValue("C$footerRow", 'Miftahul Jannah, S.Pd');
                $sheet->mergeCells("C$footerRow:D$footerRow");
                $sheet->getStyle("C$footerRow:D$footerRow")->applyFromArray([
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->setCellValue("E$footerRow", 'Eni Nur Hayati, S.Pd.I');
                $footerRow++;

                $sheet->getStyle("B$lastRow:E$footerRow")->applyFromArray([
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
