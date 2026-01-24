<?php

namespace App\Console\Commands;

use App\Notifications\AppointmentVerificationCode;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test sending verification email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? 'edingrazhda17@gmail.com';
        
        $this->info('Sending test email to: ' . $email);
        
        try {
            Notification::route('mail', $email)
                ->notify(new AppointmentVerificationCode('TEST', 'Test User'));
            
            $this->info('Email sent successfully!');
            $this->info('Check your inbox at: ' . $email);
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
        }
        
        return 0;
    }
}
