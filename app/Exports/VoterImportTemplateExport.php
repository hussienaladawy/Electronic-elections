<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VoterImportTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            // No data rows, just headings
        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'email',
            'password',
            'phone',
            'national_id',
        ];
    }
}
