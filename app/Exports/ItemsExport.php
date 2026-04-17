<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ItemsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    public function collection()
    {
        return Item::with(['transactions' => function ($q) {
            $q->latest('tanggal_transaksi');
        }])->orderBy('nama_perangkat')->get();
    }

    public function title(): string
    {
        return 'Inventaris Yaksa';
    }

    public function headings(): array
    {
        return [
            'Nama Perangkat',
            'Serial Number',
            'Status',
            'Status Barang',
            'Barang Masuk Pengirim',
            'Barang Keluar Penerima',
            'Tanggal Menerima',
            'Tanggal Pengiriman',
            'OS Version',
            'Lokasi Device',
            'Description',
        ];
    }

    public function map($item): array
    {
        // Ambil transaksi terbaru untuk data pengirim/penerima/tanggal
        $latestIn = $item->transactions->where('tipe_transaksi', 'in')->first();
        $latestOut = $item->transactions->where('tipe_transaksi', 'out')->first();

        return [
            $item->nama_perangkat,
            $item->serial_number,
            $item->status,
            $item->status_barang,
            $latestIn?->pengirim ?? '-',
            $latestOut?->penerima ?? '-',
            $latestIn?->tanggal_transaksi?->format('d/m/Y') ?? '',
            $latestOut?->tanggal_transaksi?->format('d/m/Y') ?? '',
            $item->os_version,
            $item->lokasi_device,
            $item->description,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,  // Nama Perangkat
            'B' => 22,  // Serial Number
            'C' => 16,  // Status
            'D' => 18,  // Status Barang
            'E' => 30,  // Pengirim
            'F' => 30,  // Penerima
            'G' => 16,  // Tanggal Menerima
            'H' => 18,  // Tanggal Pengiriman
            'I' => 12,  // OS Version
            'J' => 18,  // Lokasi Device
            'K' => 40,  // Description
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // Header styling
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DC2626'], // Yaksa Red
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Header row height
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Data styling
        if ($lastRow > 1) {
            $sheet->getStyle('A2:K' . $lastRow)->applyFromArray([
                'font' => ['size' => 10],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E5E7EB'],
                    ],
                ],
            ]);

            // Zebra striping
            for ($row = 2; $row <= $lastRow; $row++) {
                if ($row % 2 === 0) {
                    $sheet->getStyle('A' . $row . ':K' . $row)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'FEF2F2'], // light red tint
                        ],
                    ]);
                }
            }

            // Conditional coloring for Status column (C)
            for ($row = 2; $row <= $lastRow; $row++) {
                $statusVal = $sheet->getCell('C' . $row)->getValue();
                $color = match ($statusVal) {
                    'Ready' => '059669',           // green
                    'Barang Keluar' => 'D97706',   // amber
                    'Barang RMA' => 'DC2626',      // red
                    'Milik Internal', 'Digunakan Internal' => '2563EB', // blue
                    default => '374151',
                };
                $sheet->getStyle('C' . $row)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => $color]],
                ]);
            }
        }

        // Freeze header
        $sheet->freezePane('A2');

        // Auto-filter
        $sheet->setAutoFilter('A1:K' . $lastRow);

        return [];
    }
}
