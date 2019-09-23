<?php

namespace App\Console\Commands;

use App\Mail\sendgridEmail;
use App\User;
use Illuminate\Console\Command;

class ResendVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resend:verification {--url=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resend verification mail to all un-verified users.';

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
        $url = $this->option('url');
        $link = null;
        $emailService = new sendgridEmail();
        $users = User::whereNull('verified')->whereHas('role', function ($q) {
            $q->where('role_id', 2);
        })->get();
        foreach ($users as $user) {
            if ($url) {
                $link = $url . '/candidate/verify-account/' . $user->remember_token;
            }
            $emailService->sendVerificationEmailDefault($user, $user->remember_token, $link);
        }
        echo $users->count() . ' Emails sent out.';
    }
}
