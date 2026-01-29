<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class GeneralExport implements FromArray
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
}

