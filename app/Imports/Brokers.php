<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Storage;
use \Carbon\Carbon;
use DB;
use Helper;
use App\Models\BrokerList;

class Brokers implements ToCollection, WithHeadingRow
{
    protected $user_id;

    public function __construct($user_id = null)
    {
        $this->user_id = $user_id;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row)
        {
            if (!isset($row['broker_name']) || empty($row['broker_name']))
            {
                continue;
            }

            $data = $not_created_yet = null;

            if (isset($row['id']) && !empty($row['id']))
            {
                $data = BrokerList::where('id', $row['id'])->first();
            }

            if (empty($data))
            {
                $not_created_yet = true;

                $data = new BrokerList();
            }

            $data->broker_name = $row['broker_name'];
            $data->broker_email = $row['broker_email'];
            $data->region = $row['region'];
            $data->phone_no = $row['phone'];
            $data->business_name = $row['business_name'];
            $data->introducer = $row['introducer'];
            $data->broker_note = $row['broker_note'];

            $data->save();
        }
    }
}
