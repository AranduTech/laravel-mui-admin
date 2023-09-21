<?php

namespace Arandu\LaravelMuiAdmin\Commands;

use Arandu\LaravelMuiAdmin\Services\AdminService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para atualizar as permissões associadas à role admin, em relação às models existentes.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(AdminService $admin)
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
        $role = Role::where('name', config('admin.roles.admin', 'admin'))->first();
        $role->givePermissionTo(Permission::all());

        return 0;
    }
}
