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

class IncomingGoodsExport implements FromCollection, WithEvents, WithHeadings, WithMapping, WithStyles
{
    protected $incomingGoods;

    protected $branch;

    protected $periode;

    protected int $rowNumber = 0;

    public function __construct($incomingGoods, $branch, $periode = 'Semua Data')
    {
        $this->incomingGoods = $incomingGoods;
        $this->branch = $branch;
        $this->periode = $periode;
    }

    public function collection(): Collection
    {
        return $this->incomingGoods;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Produk',
            'Kode',
            'Supplier',
            'Jumlah',
            'Harga Beli',
            'Total Biaya',
            'Cabang',
            'Petugas',
        ];
    }

    public function map($incomingGood): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            Carbon::parse($incomingGood->tanggal_masuk)->format('d/m/Y'),
            $incomingGood->product->nama ?? '-',
            $incomingGood->product->kode ?? '-',
            $incomingGood->product->supplier->nama ?? '-',
            $incomingGood->jumlah,
            $incomingGood->harga_beli,
            $incomingGood->harga_beli * $incomingGood->jumlah,
            $incomingGood->branch->nama ?? '-',
            $incomingGood->user->first_name ?? '-',
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

                $sheet->insertNewRowBefore(1, 7);

                $sheet->mergeCells('A1:J1');
                $sheet->setCellValue('A1', 'LAPORAN BARANG MASUK - Minimarket Pak Jayusman');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A3:J3');
                $sheet->setCellValue('A3', 'Periode: '.$this->periode);
                $sheet->getStyle('A3')->getFont()->setSize(11);

                $sheet->mergeCells('A4:J4');
                $sheet->setCellValue('A4', 'Cabang: '.($this->branch ? $this->branch->nama : 'Semua Cabang'));
                $sheet->getStyle('A4')->getFont()->setSize(11);

                $sheet->mergeCells('A5:J5');
                $sheet->setCellValue('A5', 'Tanggal Cetak: '.now()->translatedFormat('d F Y H:i'));
                $sheet->getStyle('A5')->getFont()->setSize(11);

                $highestRow = $sheet->getHighestRow();

                $sheet->getStyle('F8:F'.$highestRow)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('G8:H'.$highestRow)->getNumberFormat()->setFormatCode('Rp #,##0');

                foreach (range('A', 'J') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
