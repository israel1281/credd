<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanClearedNotify extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public $amount;

    public function __construct(String $amount)
    {
        $this->amount = $amount;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->subject(config('app.name').' Loan Updates')
                                ->greeting('Hi '.$notifiable->first_name.',')
                                ->line('')
                                ->line('Your '.config('app.name').' loan balance of '.$this->amount.' has being cleared successfully! You can now request for another loan.')
                                ->action('Go to Dashboard', url(spaUrlBuilder('/dashboard')));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Loan Updates',
            'message' => 'Your loan of '.$this->amount.' has being cleared!',
            'url' => 'javascript:;'
        ];
    }
}
