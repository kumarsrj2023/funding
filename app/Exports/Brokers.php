<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use DB;

class Brokers implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('wp_introducers_info')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Broker Name',
            'Broker Email',
            'Region',
            'Phone',
            'Business Name',
            'Introducer',
            'Broker Note',
        ];
    }

    public function map($data): array
    {
        return [
            $data->id,
            $data->broker_name,
            $data->broker_email,
            $data->region,
            $data->phone_no,
            $data->business_name,
            $data->introducer,
            $data->broker_note,
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
