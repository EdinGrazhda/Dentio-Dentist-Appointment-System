<?php

namespace App\Console\Commands;

use App\Models\VerificationCode;
use Illuminate\Console\Command;

class CleanupExpiredVerificationCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verification:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired verification codes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deleted = VerificationCode::where('expires_at', '<', now())
            ->orWhere('used', true)
            ->delete();

        $this->info("Cleaned up {$deleted} verification code(s).");

        return Command::SUCCESS;
    }
}

