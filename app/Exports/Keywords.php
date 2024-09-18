<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use DB;

class Keywords implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {   
        return DB::table('keywords')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Search Term1',
            'Search Term2',
            'Excluding',
            'Amount',
            'Description',
            'Description2',
            'Deal Specific',
        ];
    }

    public function map($data): array
    {
        return [
            $data->id,
            $data->SearchTerm1,
            $data->SearchTerm2,
            $data->Excluding,
            $data->Amount,
            $data->Description,
            $data->Description2,
            $data->DealSpecific,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'D' => 55,
            'F' => 20,
            'G' => 20,
            'H' => 20,            
        ];
    }
}
