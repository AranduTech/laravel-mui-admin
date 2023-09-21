<?php

namespace Database\Seeders;

use Arandu\LaravelMuiAdmin\Services\AdminService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(AdminService $admin)
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $classes = $admin->getModelsWithCrudSupport()->map(function ($className) {
            return Str::plural((new $className())->getSchemaName());
        });

        foreach ($classes as $class) {
            // create permissions
            Permission::firstOrCreate(['name' => 'create ' . $class]);
            Permission::firstOrCreate(['name' => 'read ' . $class]);
            Permission::firstOrCreate(['name' => 'update ' . $class]);
            Permission::firstOrCreate(['name' => 'delete ' . $class]);
        }

        // create roles and assign created permissions
        $role = Role::firstOrCreate(['name' => config('admin.roles.admin', 'admin')]);
        $role->givePermissionTo(Permission::all());

        Role::firstOrCreate(['name' => config('admin.roles.subscriber', 'subscriber')]);
    }
}
