<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verify Email Address')
                ->line('Please click the button below to verify your email address.')
                ->action('Verify Email Address', $url)
                ->line('If you did not create an account, no further action is required.');
        });

        Gate::define('update-balance', function (User $user) {
            $authorizedUsers = User::where('email', 'codedcrystal@gmail.com')
                ->orWhere('email', 'dreamor47@gmail.com')
                ->orWhere('email', 'dreamor48@gmail.com')->get();
            foreach($authorizedUsers as $authorizedUser) {
                if($authorizedUser->email == $user->email) {
                    return true;
                }
            }
            return false;
            return User::where('email', 'codedcrystal@gmail.com');
        });
    }
}
