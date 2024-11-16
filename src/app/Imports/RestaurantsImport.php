<?php

namespace App\Imports;

use App\Models\Restaurant;
use Maatwebsite\Excel\Concerns\ToModel;

class RestaurantsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        return new Restaurant([
            'user_id' => 1,
            'name' => $row[0],
            'address' => $row[1],
            'genre' => $row[2],
            'description' => $row[3],
            'image' => $row[4],
        ]);
    }
}
