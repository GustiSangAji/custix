<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('role_has_permissions')->delete();

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $menuMaster = ['master', 'master-user', 'master-role'];
        $menuWebsite = ['website', 'setting', 'orders'];
        $menuEvent = ['event', 'event-tiket', 'event-stockin'];

        $permissionsByRole = [
            'admin' => ['dashboard', ...$menuEvent, ...$menuMaster, ...$menuWebsite],
        ];

        $insertPermissions = fn ($role) => collect($permissionsByRole[$role])
            ->map(function ($name) {
                $check = Permission::where('name', $name)->first();

                if (!$check) {
                    return Permission::create([
                        'name' => $name,
                        'guard_name' => 'api',
                    ])->id;
                }

                return $check->id;
            })
            ->toArray();

        $permissionIdsByRole = [
            'admin' => $insertPermissions('admin')
        ];

        foreach ($permissionIdsByRole as $roleName => $permissionIds) {
            $role = Role::where('name', $roleName)->first();

            // Cek apakah role ditemukan
            if ($role) {
                DB::table('role_has_permissions')
                    ->insert(
                        collect($permissionIds)->map(fn ($id) => [
                            'role_id' => $role->id,
                            'permission_id' => $id,
                        ])->toArray()
                    );
            } else {
                // Role tidak ditemukan, Anda bisa menambahkan logika di sini
                echo "Role $roleName not found.\n";
            }
        }
    }
}
