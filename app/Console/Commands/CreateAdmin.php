<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Allows to create an admin account directly through CLI';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {                

        $this->line('Adding core settings into tables');

        $admin_name = $this->askValid('Input administrator full name: ', 'admin_name', ['required', 'min:3']);
        $admin_email = $this->askValid('Input administrator email: ', 'admin_email', ['required', 'email']);
        $admin_pass = $this->askValid('Input administrator password: ', 'admin_pass', ['required', 'min:8']);

        // Add administrator account
        $this->line('Adding administrator account');
        User::updateOrInsert(['email' => $admin_email], [
            'name' => $admin_name ?? 'Admin',
            'email' => $admin_email,            
            'role' => 'admin',
            'password' => Hash::make($admin_pass),
            'email_verified_at' => now(),
            'created_at' => now()
        ]);

        $this->info('The admin was added!');
    }


    protected function askValid($question, $field, $rules)
    {
        $value = $this->ask($question);

        if ($message = $this->validateInput($rules, $field, $value)) {
            $this->error($message);

            return $this->askValid($question, $field, $rules);
        }

        return $value;
    }


    protected function validateInput($rules, $fieldName, $value)
    {
        $validator = Validator::make([
            $fieldName => $value
        ], [
            $fieldName => $rules
        ]);

        return $validator->fails()
            ? $validator->errors()->first($fieldName)
            : null;
    }
}
