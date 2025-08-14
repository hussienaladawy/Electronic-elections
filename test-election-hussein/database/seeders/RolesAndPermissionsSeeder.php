<?php
namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Voter;
use App\Models\Assistant;
use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
  
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');

        // Define permissions for each guard
        $permissionsByGuard = [
            'super_admin' => [
                'manage roles', 'manage users', 'manage elections', 'manage settings',
                'review reports', 'manage voters', 'print cards', 'manage candidates',
                'review votes', 'verify-vote', 'vote', 'view elections', 'cast vote',
                'create super admin', 'delete super admin', 'manage admins', 'manage assistants',
            ],
            'admin' => [
                'manage admins', 'manage assistants', 'manage elections', 'manage settings',
                'review reports', 'manage voters', 'print cards',
                'manage candidates', 'review votes', 'verify-vote',
                'vote', 'view elections', 'cast vote'
            ],
            'assistant' => [
                'review reports', 'print cards', 'verify-vote', 'view elections'
            ],
            'voter' => [
                'view elections', 'vote', 'cast vote'
            ],
        ];

        // Create permissions and roles for each guard
        foreach ($permissionsByGuard as $guard => $permissions) {
            $role = Role::firstOrCreate(['name' => $guard, 'guard_name' => $guard]);

            $permissionsToAssign = [];
            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => $guard
                ]);
                $permissionsToAssign[] = $permission;
            }

            $role->syncPermissions($permissionsToAssign);
        }

        $this->assignRolesToAllModels();
    }

    private function assignRolesToAllModels()
    {
        $models = [
            'super_admin' => SuperAdmin::class,
            'admin' => Admin::class,
            'assistant' => Assistant::class,
            'voter' => Voter::class,
        ];

        foreach ($models as $guard => $modelClass) {
            $role = Role::where('name', $guard)->where('guard_name', $guard)->first();

            if ($role) {
                $modelClass::all()->each(function ($model) use ($role, $guard) {
                    if (!$model->hasRole($guard, $guard)) {
                        $model->assignRole($role);
                        echo "✅ تم تعيين دور {$guard} للمستخدم رقم {$model->id}\n";
                    }
                });
            }
        }
    }
}