<?php

namespace App\Imports;

use App\Models\Housing;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class HousingImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Check if mobile_number is not empty
            if (!empty($row[0])) {
                Housing::create([
                    'mobile_number' => $row[0],
                    'name' => $row[1],
                    'unit' => $row[2],
                    'utility' => $row[3],
                    'additional_utility' => $row[4],
                    'total_utility' => $row[5],
                    'status' => $row[6],
                ]);
            }
        }

    }
}
