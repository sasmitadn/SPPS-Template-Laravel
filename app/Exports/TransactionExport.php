<?php

namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class TransactionExport implements FromCollection, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{

    protected $search, $data;

    public function __construct($search) {
        $this->search = $search;  
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $search = $this->search;
        $query = Transaction::query()
                    ->join('students', 'students.code', '=', 'transactions.id_student') // Join tabel student
                    ->select('transactions.*') // Pilih kolom transaction agar tidak bentrok
                    ->orderBy('students.name', 'asc');

        if (!empty($search)) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('code', 'like', "%$search%");
            });
        }
        $this->data = $query->with('student')->with('invoice')->get();
        return $this->data;
    }

    public function startCell(): string
    {
        return 'B8';
    }

    public function map($item): array
    {
        return [
            $item['id'],
            $item['student']['code'],
            $item['student']['name'],
            $item['invoice']['type'] == 1 ? 'Kredit' : 'One-Time Payment',
            number_format($item['invoice']['amount'], 0, ",", "."),
            $item['created_at']->translatedFormat('d F Y')
        ];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Tambahkan Kop Surat
                $sheet->mergeCells('B2:G2');
                $sheet->mergeCells('B3:G3');
                $sheet->mergeCells('B4:G4');
                $sheet->mergeCells('B5:G5');
                $sheet->mergeCells('B6:G6');

                $sheet->setCellValue('B3', 'LAPORAN TRANSAKSI PEMBAYARAN');
                $sheet->setCellValue('B4', 'BUSTANUL ATHFAL AISYIYAH 08 GROGOL');
                $sheet->setCellValue('B5', 'Mantung Rt.04 Rw. 05 Sanggrahan, Grogol, Sukoharjo');

                // Pindahkan header ke A6
                $sheet->setCellValue('B7', 'Kode Transaksi');
                $sheet->setCellValue('C7', 'Kode Siswa');
                $sheet->setCellValue('D7', 'Nama Siswa');
                $sheet->setCellValue('E7', 'Jenis Pembayaran');
                $sheet->setCellValue('F7', 'Nominal');
                $sheet->setCellValue('G7', 'Tanggal');

                // Styling Kop Surat
                $sheet->getStyle('B2:G6')->applyFromArray([
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
                $sheet->getStyle('B7:G7')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);

                // body
                $lastRow = count($this->data) + 7;
                $sheet->getStyle("B7:G$lastRow")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                $sheet->getStyle("F7:F$lastRow")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
                ]);


                // Footer
                $lastRow++;
                $footerRow = $lastRow;
                $sheet->setCellValue("B$footerRow", 'Mengesahkan,'); $footerRow++;
                $date = Carbon::now()->translatedFormat('d F Y');
                $sheet->setCellValue("B$footerRow", 'Grogol, ' . $date); $footerRow+=2;
                $sheet->setCellValue("B$footerRow", 'Kepala BA Aisyiyah 08 Grogol');

                $sheet->setCellValue("D$footerRow", 'Koordinator Pendidikan');
                $sheet->mergeCells("D$footerRow:E$footerRow");
                $sheet->getStyle("D$footerRow:E$footerRow")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->setCellValue("G$footerRow", 'Pembuat Laporan');
                $footerRow+=3;
                $sheet->setCellValue("B$footerRow", 'Sitti Istikomah, S.Pd.I');

                $sheet->setCellValue("D$footerRow", 'Miftahul Jannah, S.Pd');
                $sheet->mergeCells("D$footerRow:E$footerRow");
                $sheet->getStyle("D$footerRow:E$footerRow")->applyFromArray([
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->setCellValue("G$footerRow", 'Eni Nur Hayati, S.Pd.I');
                $footerRow++;

                $sheet->getStyle("B$lastRow:G$footerRow")->applyFromArray([
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
