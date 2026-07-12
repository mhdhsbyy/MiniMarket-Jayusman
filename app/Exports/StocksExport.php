<?php

namespace App\Exports;

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

class StocksExport implements FromCollection, WithEvents, WithHeadings, WithMapping, WithStyles
{
    protected $stocks;

    protected $branch;

    protected $category;

    protected int $rowNumber = 0;

    public function __construct($stocks, $branch, $category)
    {
        $this->stocks = $stocks;
        $this->branch = $branch;
        $this->category = $category;
    }

    public function collection(): Collection
    {
        return $this->stocks;
    }

    public function headings(): array
    {
        return [
            'No',
            'Produk',
            'Kode',
            'Kategori',
            'Cabang',
            'Stok',
            'Status',
        ];
    }

    public function map($stock): array
    {
        $this->rowNumber++;

        $jumlahStok = $stock->jumlah_stok;

        if ($jumlahStok <= 0) {
            $status = 'Habis';
        } elseif ($jumlahStok < 30) {
            $status = 'Menipis';
        } else {
            $status = 'Aman';
        }

        return [
            $this->rowNumber,
            $stock->product->nama ?? '-',
            $stock->product->kode ?? '-',
            $stock->product->category->nama ?? '-',
            $stock->branch->nama ?? '-',
            $jumlahStok,
            $status,
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
                $sheet->setCellValue('A1', 'LAPORAN STOK BARANG - Minimarket Pak Jayusman');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A3:G3');
                $sheet->setCellValue('A3', 'Cabang: '.($this->branch ? $this->branch->nama : 'Semua Cabang'));
                $sheet->getStyle('A3')->getFont()->setSize(11);

                $sheet->mergeCells('A4:G4');
                $sheet->setCellValue('A4', 'Kategori: '.($this->category ? $this->category->nama : 'Semua Kategori'));
                $sheet->getStyle('A4')->getFont()->setSize(11);

                $sheet->mergeCells('A5:G5');
                $sheet->setCellValue('A5', 'Tanggal Cetak: '.now()->translatedFormat('d F Y H:i'));
                $sheet->getStyle('A5')->getFont()->setSize(11);

                $highestRow = $sheet->getHighestRow();

                $sheet->getStyle('F8:F'.$highestRow)->getNumberFormat()->setFormatCode('#,##0');

                foreach (range('A', 'G') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
