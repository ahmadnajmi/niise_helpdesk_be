<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class GeneralExport implements FromArray, WithStyles, ShouldAutoSize
{
    protected array $data;
    protected $title;

    public function __construct(array $data, $title = '')
    {
        $this->data = $data;
        $this->title = $title;
    }

    public function array(): array
    {
        return [
            [ $this->title ], // Row 1: Title
            [ '' ],             // Row 2: Empty row
            ...$this->data       // Row 3+: Data
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:F1');
        return [
            'A:Z' => [
                'alignment' => [
                    'wrapText' => true,
                ],
            ],
        ];
    }
}

