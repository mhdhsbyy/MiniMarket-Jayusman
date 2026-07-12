<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\BeforeWriting;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromCollection, WithEvents, WithHeadings, WithMapping, WithStyles
{
    protected $periode;

    protected $transactions;

    protected $branch;

    protected int $rowNumber = 0;

    public function __construct($transactions, $periode, $branch = null)
    {
        $this->transactions = $transactions;
        $this->periode = $periode;
        $this->branch = $branch;
    }

    public function collection(): Collection
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode',
            'Tanggal',
            'Cabang',
            'Kota',
            'Kasir',
            'Total Bayar',
        ];
    }

    public function map($transaction): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            'TRX-'.str_pad($transaction->id, 5, '0', STR_PAD_LEFT),
            Carbon::parse($transaction->tanggal_transaksi)->translatedFormat('d F Y H:i'),
            $transaction->branch->nama ?? '-',
            $transaction->branch->kota ?? '-',
            trim(($transaction->cashier->first_name ?? '').' '.($transaction->cashier->last_name ?? '')),
            $transaction->total_bayar,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F172A']],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function (BeforeWriting $event) {
                $sheet = $event->getWriter()->getActiveSheet();

                $sheet->insertNewRowBefore(1, 6);

                $sheet->mergeCells('A1:G1');
                $sheet->setCellValue('A1', 'LAPORAN TRANSAKSI - Minimarket Pak Jayusman');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A3:G3');
                $sheet->setCellValue('A3', 'Periode: '.$this->periode);
                $sheet->getStyle('A3')->getFont()->setSize(11);

                $sheet->mergeCells('A4:G4');
                $sheet->setCellValue('A4', 'Cabang: '.($this->branch ? $this->branch->nama : 'Semua Cabang'));
                $sheet->getStyle('A4')->getFont()->setSize(11);

                $sheet->mergeCells('A5:G5');
                $sheet->setCellValue('A5', 'Tanggal Cetak: '.now()->translatedFormat('d F Y H:i'));
                $sheet->getStyle('A5')->getFont()->setSize(11);

                $highestRow = $sheet->getHighestRow();

                $sheet->getStyle('G8:G'.$highestRow)->getNumberFormat()->setFormatCode('#,##0');

                foreach (range('A', 'G') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
