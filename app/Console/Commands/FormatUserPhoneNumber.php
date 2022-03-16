<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class FormatUserPhoneNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'format:phone-number';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Replace preceeding \'0\' in user\'s phone number with \'234\'';

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
            $phoneNumberSplit = str_split($user->phone);
            if ($phoneNumberSplit[0] === '0') {
                $user->phone = "234".join(array_slice($phoneNumberSplit, 1, 10));
                $user->save();
                $this->info($user->phone);
            }
        }
    }
}
