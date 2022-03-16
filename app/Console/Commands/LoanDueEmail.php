<?php

namespace App\Console\Commands;

use App\Models\LoanRequest;
use App\Notifications\LoanAlmostDue;
use App\Notifications\LoanDue;
use App\Notifications\LoanTomorrowDue;
use Illuminate\Console\Command;

class LoanDueEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loan:due {-O|--overdue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to users when loan is due.';

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
        $loanRequests = LoanRequest::active()->get();
        foreach($loanRequests as $loanRequest) {
            $currentDate = now();
            $expiryDate = $loanRequest->expire_at;
            $daysRemaining = $expiryDate->diffInDays($currentDate);
            if ($currentDate->greaterThan($expiryDate)) {
                if ($daysRemaining == 1 || $this->option('overdue')) {
                    $loanRequest->user->notify(new LoanDue);
                    $this->info('Loan Overdue: '.$daysRemaining);
                }
            } else {
                if ($daysRemaining == 5) {
                    $loanRequest->user->notify(new LoanAlmostDue);
                    $this->info('Loan almost due: '.$daysRemaining);
                }
                else if ($daysRemaining == 1) {
                    $loanRequest->user->notify(new LoanTomorrowDue);
                    $this->info('Loan almost due: tomorrow');
                }
                    // $this->info('Loan almost due: '.$daysRemaining);
            }

        }
        return 0;
    }
}
