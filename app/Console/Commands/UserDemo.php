<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UserDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user-demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up environment with demo user data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating demo account...');
        $this->create_demo_account();
    }
    private function create_demo_account(): void
    {
        $email = "demo@gmail.com";
        $userDemo = User::updateOrCreate(
            ['email' => $email],
            [
                'username'             => 'demo',
                'email'             => $email,
                'password'          => bcrypt('123'),
                'email_verified_at' => now(),
                'type'              => 'backend',
            ]
        );
        $userDemo
            ->settings()
            ->updateOrCreate(
            ['user_id' => $userDemo->id],
            [
                'avatar' => null,
                'first_name' => $userDemo->username,
                'last_name' => $userDemo->username,
                'address' => "PP",
                'gender' => 'male',
                'phone_number' => '087447230',
                'theme_mode' => 'dark',
            ]
        );
        setEnvironmentValue([
            'Demo_Account' => "$userDemo->email",
        ]);
        $this->info("Generated user with email: $userDemo->email and Password: $userDemo->password");
    }
}
