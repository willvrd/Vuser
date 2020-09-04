<?php

namespace Modules\Vuser\Traits;

use Modules\Vuser\Entities\Permission;

trait Permissions
{

    /**
     * Create Permissions from Module
     *
     * @param configName
     * @return void
     *
     * @throws
     */
    public function createPermissionsFromModule($configName){

        $permissions = config($configName);

        $this->line('Creating Permissions:');
        $bar = $this->output->createProgressBar(count($permissions));
        $bar->start();

        foreach ($permissions as $permission) {
            $permission = Permission::updateOrCreate(['name' => $permission]);
            $bar->advance();
        }

        $bar->finish();
        $this->line('');

    }

}
