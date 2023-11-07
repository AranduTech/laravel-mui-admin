<?php

namespace Arandu\LaravelMuiAdmin\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class CredentialsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:credentials';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin credentials for the application';

    /**
     * Create a new command instance.
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
    public function handle()
    {
        $role = null;
        try {
            //code...
            $Role = config('permission.models.role', Role::class);

            $role = $Role::where('name', config('admin.roles.admin', 'admin'))->first();
        } catch (\Throwable $th) {
            //throw $th;
            $this->error($th->getMessage());
        }

        if (!$role) {
            $this->error('The admin role does not exist. Please check if the database is migrated and seeded.');

            return 1;
        }

        $confirmed = false;

        while (!$confirmed) {

            // asks for a user name
            $name = $this->ask('What is the name of the admin user?');
            // asks for an email
            $email = $this->ask('What is the email of the admin user?');
            // asks for a password
            $password = $this->secret('What is the password of the admin user?');
            // confirms the password
            $passwordConfirmation = $this->secret('Confirm the password of the admin user?');


            $validator = Validator::make([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $passwordConfirmation,
            ], [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $error) {
                    $this->error($error);
                }

                continue;
            }

            $this->info('The user will be created with the following data:');
            $this->info('Name: ' . $name);
            $this->info('Email: ' . $email);
            if ($this->confirm('Display password?', false)) {
                $this->info('Password: ' . $password);
            }
            $confirmed = $this->confirm('Do you confirm?', true);
        }
        
        // create the user
        $User = config('auth.providers.users.model', \App\Models\User::class);

        $user = new $User();
        $user->name = $name;
        $user->email = $email;
        $user->password = \Hash::make($password);
        $user->email_verified_at = now();
        // $user->role_id = Role::byName(config('admin.roles.admin', 'admin'))->id;

        try {
            DB::beginTransaction();

            $user->save();
            $user->assignRole(config('admin.roles.admin', 'admin'));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->error($th->getMessage());

            return 1;
        }

        $this->info('The user was created successfully');

        return 0;
    }
}
