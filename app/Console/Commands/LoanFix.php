<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class LoanFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loan:multiple-request-fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix multiple active loans issue.';

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
     * @return int
     */
    public function handle()
    {
        $users = User::all();
        foreach($users as $user) {
            if ($user->wallet != null)
                if ($user->loanRequests()->active()->count() > 1) {
                    foreach($user->loanRequests()->active()->latest()->get() as $key => $loanRequest) {
                        if ($key == 0) continue;
                        $loanRequest->status_id = status_completed_id();
                        $loanRequest->save();
                        $this->info($user->name.' >> loan request date >> '.$loanRequest->created_at);
                    }
                    // $this->info($user->name.' >> active loan requests >> '.$user->loanRequests()->active()->count().' >> loan >> '.$user->wallet->loan_amt);
                }
        }
    }
}
