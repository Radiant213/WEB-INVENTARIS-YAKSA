<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ItemsExport implements WithMultipleSheets
{
    protected $gudang;

    public function __construct($gudang = 'universal')
    {
        $this->gudang = $gudang;
    }

    public function sheets(): array
    {
        $sheets = [];

        if ($this->gudang === 'universal') {
            $sheets[] = new ItemsPerGudangSheet('jakarta');
            $sheets[] = new ItemsPerGudangSheet('bali');
            $sheets[] = new ItemsPerGudangSheet('sfp');
        } else {
            $sheets[] = new ItemsPerGudangSheet($this->gudang);
        }

        return $sheets;
    }
}
