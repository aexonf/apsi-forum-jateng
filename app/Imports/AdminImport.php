<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AdminImport implements  ToModel, WithStartRow
{


    public function model(array $row)
    {
        if ( $row[0] === 'Username' &&  $row[1] === 'Password' ) {
            return null;
        }
       User::create([
            "username" => $row[0],
            "password" => Hash::make($row[1]),
            "role" => "admin",
       ]);
    }
    public function startRow(): int
    {
        return 2;
    }
}
