<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateAdminUser extends Command
{
    protected $signature = 'catering:create-admin {email?} {--name=Admin E-Catering}';

    protected $description = 'Create the first administrator account for E-Catering.';

    public function handle(): int
    {
        $email = $this->argument('email') ?: $this->ask('Email admin');
        $password = $this->secret('Password admin minimal 8 karakter');

        $validator = Validator::make(compact('email', 'password'), [
            'email' => ['required', 'email'],
            'password' => ['required', Password::min(8)],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        User::updateOrCreate(
            ['email' => $email],
            ['name' => $this->option('name'), 'password' => Hash::make($password), 'role' => User::ROLE_ADMIN]
        );

        $this->info('Admin berhasil dibuat: '.$email);

        return self::SUCCESS;
    }
}
