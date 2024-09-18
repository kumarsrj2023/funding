<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use DB;
use App\Models\DirectorInfo;

class Directors implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnWidths
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('wp_director_info')->where('wp_business_info_id', $this->id)->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Address',
            'Gender',
            'Date Of Birth',
            'Nationality',
            'Occupation',
            'Date Appointed',
        ];
    }

    public function map($data): array
    {
        return [
            $data->id,
            $data->name,
            $data->address_simple_value . ' ' . $data->address_street . ' ' . $data->address_city . ' ' . $data->address_postal_code,
            $data->gender,
            $data->date_of_birth,
            $data->nationality,
            $data->occupation,
            $data->date_appointed,
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
