<?php

namespace Modules\Vuser\Console;

use Illuminate\Console\Command;

use Modules\Vuser\Entities\Role;

use Spatie\Permission\PermissionRegistrar;

use Modules\Vuser\Traits\Permissions;

class RolePermissionCommand extends Command
{

    use Permissions;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'user:role-permission:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create roles and permissions.';

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

        try{

            $this->line('');
            $this->info('WELCOME TO USER MODULE:');

            $email = $this->ask('Enter SuperAdmin email');
            $password = $this->secret('Enter SuperAdmin password (min 8)');

            if(!$this->validatorInputs($email,$password)){

                 // Reset cached roles and permissions
                app()[PermissionRegistrar::class]->forgetCachedPermissions();

                $roles = ["super-admin", "admin", "user"];

                $this->createRoles($roles);
                $this->createPermissionsFromModule("vuser.permissions");
                $this->createDefaultUsers($roles,$email,$password);

                $this->info("THAT'S ALL MY FRIEND ;)");
            }

        }catch(\Exception $e){
            $this->line('');
            $this->error(":O ERROR:");
            $this->line($e);
            $this->error("NO MORE");
        }

    }

    public function validatorInputs($email,$password){

        $validator = \Validator::make([
            'email' => $email,
            'password' => $password,
        ], [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ]);

        if ($validator->fails()) {
            $this->comment('Errors:');
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }
        return 0;
    }

    public function createRoles($roles){

        $this->line('Creating Roles:');
        $bar = $this->output->createProgressBar(count($roles));
        $bar->start();
        foreach ($roles as $role) {
            $role = Role::updateOrCreate(['name' => $role]);
            $bar->advance();
        }
        $bar->finish();
        $this->line('');

    }

    public function createDefaultUsers($roles,$email,$password){

        $this->line('Creating Default Users:');

        $bar = $this->output->createProgressBar(3);
        $bar->start();

        // Super Admin
        $role = Role::where('name', $roles[0])->first();
        $data = array(
            'name' => 'William Verde',
            'email' => $email,
            'password' => \Hash::make($password)
        );
        $user = Factory(\Modules\Vuser\Entities\User::class)->create($data);
        $user->assignRole($role);
        $bar->advance();

        // Admin
        $role = Role::where('name', $roles[1])->first();
        $password = 'admin';
        $data = array(
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => \Hash::make($password)
        );
        $user = Factory(\Modules\Vuser\Entities\User::class)->create($data);
        $user->assignRole($role);
        $bar->advance();

        // User
        $role = Role::where('name', $roles[2])->first();
        $password = 'user';
        $data = array(
            'name' => 'UserTest',
            'email' => 'user@example.com',
            'password' => \Hash::make($password)
        );

        $user = Factory(\Modules\Vuser\Entities\User::class)->create($data);
        $user->assignRole($role);
        $bar->advance();

        $bar->finish();
        $this->line('');

    }

}
