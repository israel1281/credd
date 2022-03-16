<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class VerifyBvnNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phone:reverify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Reverify user phone number that does not match bvn number.";

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
        // Retrieve users with verified bvn
        $users = User::has('bvn')->get();
        foreach($users as $user) {
            // Reverify Users without bvn
            if (!$user->bvn_verified) {
                $user->phone_verified_at = null;
                $user->save();
                $this->info("Reverify phone number");
            } else {
                // format phone number
                //-- Get user bvn phone number
                $userBvnPhone = formatPhoneNumber($user->bvn->phone);

                // Compare bvn number with phone number to see if it match
                if ($user->phone_verified_at != null) {
                    if ($userBvnPhone != $user->phone) {
                        // if they don't match, reverify
                        $user->phone_verified_at = null;
                        $user->save();
                        $this->info("Reverify phone number");
                    }
                    else {
                        $this->info("Phone number match");
                    }
                }
            }
        }
        $this->info("Completed! That don't match bvn phone will reverify!");
    }
}
