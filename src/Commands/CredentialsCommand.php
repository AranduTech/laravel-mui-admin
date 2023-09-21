<?php

namespace Arandu\LaravelMuiAdmin\Commands;

use Illuminate\Console\Command;
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
        // asks for a user name
        $name = $this->ask('What is the name of the admin user?');
        // asks for an email
        $email = $this->ask('What is the email of the admin user?');
        // verify if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('The email is not valid');

            return 1;
        }
        // asks for a password
        $password = $this->secret('What is the password of the admin user?');
        // confirms the password
        $passwordConfirmation = $this->secret('Confirm the password of the admin user?');
        // verify if password and password confirmation are the same
        if ($password !== $passwordConfirmation) {
            $this->error('The passwords do not match');

            return 1;
        }
        // create the user
        $user = new \App\Models\User();
        $user->name = $name;
        $user->email = $email;
        $user->password = \Hash::make($password);
        $user->email_verified_at = now();
        // $user->role_id = Role::byName(config('admin.roles.admin', 'admin'))->id;

        $saved = $user->save();

        $user->assignRole(config('admin.roles.admin', 'admin'));

        if (!$saved) {
            $this->error('The user could not be created');

            return 1;
        }
        $this->info('The user was created successfully');

        return 0;
    }
}
