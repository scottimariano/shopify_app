<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CreateUserTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:create {user_id} {abilities*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a token for a specific user with defined abilities';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $abilities = $this->argument('abilities');

        $user = User::find($userId);

        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return Command::FAILURE;
        }

        $token = $user->createToken("apiToken", $abilities)->plainTextToken;

        $this->info("Token created for User ID {$userId}:");
        $this->line($token);

        return Command::SUCCESS;
    }
}
