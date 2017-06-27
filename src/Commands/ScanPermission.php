<?php

namespace iLaravel\Acl\Commands;

use App\Providers\AuthServiceProvider;
use iLaravel\Acl\Models\Permission;
use iLaravel\Acl\Models\Role;
use Illuminate\Console\Command;

class ScanPermission extends Command
{
    const removableMethods = [
        '__construct',
        'before',
    ];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan permissions';

    private $policyModelMethods = [];

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
     * @return mixed
     */
    public function handle()
    {
        $bar = $this->output->createProgressBar(4);

        $this->getPolicyModelMethods();
        $bar->advance();

        $this->addPermissions();
        $bar->advance();

        $this->removeExpirePermissions();
        $bar->advance();

        $this->superAdminAttachAllAccess();
        $bar->advance();
        $bar->finish();

        $this->info(PHP_EOL . 'Permissions scan finished.');
    }

    private function getPolicyModelMethods()
    {
        $policies = AuthServiceProvider::$policiesClone;

        foreach ($policies as $model => $policy) {
            $methods = get_class_methods($policy);

            $methods = array_diff($methods, self::removableMethods);
            $methods = array_values($methods);

            $this->policyModelMethods[$model] = $methods;
        }
    }

    private function addPermissions()
    {
        foreach ($this->policyModelMethods as $model => $methods) {
            foreach ($methods as $method) {
                Permission::firstOrCreate([
                    'model'  => $model,
                    'method' => $method,
                ]);
            }
        }
    }

    private function removeExpirePermissions()
    {
        $permissions = Permission::all();

        $removePermission = [];
        foreach ($permissions as $permission) {
            $model = $permission->model;
            $method = $permission->method;

            if (!in_array($method, $this->policyModelMethods[$model])) {
                $removePermission[] = $permission->id;
            }
        }

        Permission::whereIn('id', $removePermission)->delete();
    }

    private function superAdminAttachAllAccess()
    {
        /** @var Role $superRole */
        $superRole = Role::firstOrCreate([
            'name'  => 'super_admin',
            'label' => 'Super Admin',
        ]);

        $permissions = Permission::all();

        $superRole->givePermissions($permissions);
    }
}
