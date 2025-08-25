<?php

namespace App\Imports;

use App\Models\Voter;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

use App\Models\Admin;
use App\Models\SuperAdmin;
use App\Notifications\NewVoterRegisteredNotification;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;

class VotersImport implements ToModel, WithHeadingRow, WithEvents
{
    private $voters = [];

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $voter = new Voter([
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

        $this->voters[] = $voter;

        return $voter;
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function(AfterImport $event) {
                $admins = Admin::all();
                $superAdmins = SuperAdmin::all();

                foreach ($this->voters as $voter) {
                    foreach ($admins as $admin) {
                        $admin->notify(new NewVoterRegisteredNotification($voter));
                    }
                    foreach ($superAdmins as $superAdmin) {
                        $superAdmin->notify(new NewVoterRegisteredNotification($voter));
                    }
                }
            },
        ];
    }