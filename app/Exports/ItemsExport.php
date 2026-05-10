<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ItemsExport implements WithMultipleSheets
{
    protected $gudang;
    protected $startDate;
    protected $endDate;

    public function __construct($gudang = 'universal', $startDate = null, $endDate = null)
    {
        $this->gudang = $gudang;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function sheets(): array
    {
        $sheets = [];

        if ($this->gudang === 'universal') {
            $sheets[] = new ItemsPerGudangSheet('jakarta', $this->startDate, $this->endDate);
            $sheets[] = new ItemsPerGudangSheet('bali', $this->startDate, $this->endDate);
            $sheets[] = new ItemsPerGudangSheet('sfp', $this->startDate, $this->endDate);
        } else {
            $sheets[] = new ItemsPerGudangSheet($this->gudang, $this->startDate, $this->endDate);
        }

        return $sheets;
    }
}
