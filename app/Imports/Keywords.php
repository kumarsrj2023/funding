<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Storage;
use \Carbon\Carbon;
use DB;
use Helper;
use App\Models\Keyword;

class Keywords implements ToCollection, WithHeadingRow
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
            if (!isset($row['search_term1']) || empty($row['search_term1']))
            {
                continue;
            }

            $data = $not_created_yet = null;

            if (isset($row['id']) && !empty($row['id']))
            {
                $data = Keyword::where('id', $row['id'])->first();
            }

            if (empty($data))
            {
                $not_created_yet = true;

                $data = new Keyword();
            }

            $data->SearchTerm1 = $row['search_term1'];
            $data->SearchTerm2 = $row['search_term2'];
            $data->Excluding = $row['excluding'];
            $data->Amount = $row['amount'];
            $data->Description = $row['description'];
            $data->Description2 = $row['description2'];
            $data->DealSpecific = $row['deal_specific'];

            $data->save();
        }
    }
}
