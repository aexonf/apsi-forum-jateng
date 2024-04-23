<?php

namespace App\Imports;

use App\Models\Supervisors;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SupervissorImport implements ToModel, WithStartRow
{
    /**
     * @param Collection $collection
     */
    public function model(array $row)
    {

        if (
            $row[0] === 'ID Number' &&
            $row[1] === 'Nama' &&
            $row[2] === 'Nomer HP' &&
            $row[3] === 'Tingkat' &&
            $row[4] === 'Label' &&
            $row[5] === 'Email' &&
            $row[6] === 'Username' &&
            $row[7] === 'Password'
        ) {
            return null;
        }


        $user = User::create([
            "username" => $row[6],
            "password" => Hash::make($row[7]),
            "is_password_change" => false,
            "role" => "supervisor",
        ]);


        Supervisors::create([
            "id_number" => $row[0],
            "name" => $row[1],
            "phone_number" => $row[2],
            "level" => $row[3],
            "label" => $row[4],
            "email" => $row[5],
            "img_url" => "",
            "user_id" => $user->id,
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
