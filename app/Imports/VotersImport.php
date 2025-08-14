<?php

namespace App\Imports;

use App\Models\Voter;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class VotersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
 public function model(array $row)
    {
        return new Voter([
            'name'       => $row['name'],
            'email'      => $row['email'],
            'password'   => Hash::make('123456'), // كلمة مرور افتراضية
            'phone'      => $row['phone'],
            'national_id'=> $row['national_id'],
            'gender'     => $row['gender'] ?? null,
            'address'    => $row['address'] ?? 'عنوان غير معروف',
            'is_eligible'=> 1,
            'status'     => 1,
            'has_voted'  => 0,
        ]);
    }
}