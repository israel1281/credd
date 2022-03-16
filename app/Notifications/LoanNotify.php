<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanNotify extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    const ACCEPT = 'accept';
    const REJECT = 'reject';

    public $status;
    public $amount;

    public function __construct(String $status, String $amount)
    {
        $this->status = $status;
        $this->amount = $amount;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
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
        $mailable = (new MailMessage)->subject(config('app.name').' Loan Request Updates')
                    ->greeting('Hi '.$notifiable->first_name.',')
                    ->line('');
        if (self::ACCEPT == $this->status) {
            $mailable->line('Your loan application of '.$this->amount.' has been approved.');
        } else if (self::REJECT == $this->status) {
            $mailable->line('Your loan application of '.$this->amount.' has been rejected.');
        }
        return $mailable->action('Go to Dashboard', url(spaUrlBuilder('/dashboard')));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $data = [
            'title' => 'Loan Request Updates',
            'message' => '',
            'url' => 'javascript:;'
        ];
        if (self::ACCEPT == $this->status) {
            $data['message'] = 'Your loan application of '.$this->amount.' has been approved.';
        } else {
            $data['message'] = 'Your loan application of '.$this->amount.' has been rejected.';
        }
        return $data;
    }
}
