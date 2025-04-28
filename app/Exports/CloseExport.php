<?php

namespace App\Exports;

use App\Models\Student;
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

class CloseExport implements FromCollection, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{

    protected $status, $data;

    public function __construct($status)
    {
        $this->status = $status;
    }

    public function collection()
    {
        $data = Student::query();

        if ($this->status !== '' && $this->status !== null) {
            $data = Student::where('status', '=', $this->status);
        }

        $this->data = $data->get();
        return $this->data->map(function ($student) {
            $selected_invoices = json_decode($student->selected_invoices, true) ?? [];
            $invoices = \App\Models\Invoice::whereIn('id', $selected_invoices)->get();
            $transactions = \App\Models\Transaction::where([
                ['id_student', '=', $student->code],
                ['status', '=', '1']
            ])->with('invoice')->get();

            $totalTagihan = 0;
            foreach ($invoices as $invoice) {
                if ($invoice->status != 1) continue;
                $totalTagihan += ($invoice->type == 1) ? ($invoice->amount * $invoice->credit_amount) : $invoice->amount;
            }

            $totalBayar = $transactions->sum(fn($t) => optional($t->invoice)->amount ?? 0);

            return [
                'name'   => $student->name,
                'class'  => $student->class,
                'tagihan'=> $totalTagihan,
                'bayar'  => $totalBayar,
                'status' => $student->status == 1 ? 'Lunas' : 'Belum Lunas',
            ];
        });
    }

    public function startCell(): string
    {
        return 'B8';
    }

    public function map($student): array
    {
        return [
            $student['name'],
            $student['class'],
            number_format($student['tagihan'], 0, ',', '.'),
            number_format($student['bayar'], 0, ',', '.'),
            $student['status'],
        ];
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

                $sheet->setCellValue('B3', 'LAPORAN KEUANGAN');
                $sheet->setCellValue('B4', 'BUSTANUL ATHFAL AISYIYAH 08 GROGOL');
                $sheet->setCellValue('B5', 'Mantung Rt.04 Rw. 05 Sanggrahan, Grogol, Sukoharjo');

                // Pindahkan header ke A6
                $sheet->setCellValue('B7', 'Nama Siswa');
                $sheet->setCellValue('C7', 'Kelas');
                $sheet->setCellValue('D7', 'Total Tagihan');
                $sheet->setCellValue('E7', 'Total Bayar');
                $sheet->setCellValue('F7', 'Status');

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
                $lastRow = count($this->data) + 7;
                $sheet->getStyle("B7:F$lastRow")->applyFromArray([
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                $sheet->getStyle("D7:E$lastRow")->applyFromArray([
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
                $sheet->setCellValue("F$footerRow", 'Pembuat Laporan');
                $footerRow+=3;
                $sheet->setCellValue("B$footerRow", 'Sitti Istikomah, S.Pd.I');
                $sheet->setCellValue("D$footerRow", 'Miftahul Jannah, S.Pd');
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
