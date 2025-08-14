<?php

namespace App\Exports;

use App\Models\Voter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VotersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Voter::select(
            'id',
            'name',
            'email',
            'phone',
            'national_id',
            'gender',
            'address',
            'is_eligible',
            'has_voted'
        )->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Phone',
            'National ID',
            'Gender',
            'Address',
            'Is Eligible',
            'Has Voted'
        ];
    }
}
