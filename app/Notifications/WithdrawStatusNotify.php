<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawStatusNotify extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    const ACCEPT = 'accept';
    const REJECT = 'reject';
    const PROCESS = 'process';

    public $status;
    public $amount;
    public $reason;

    public function __construct(String $status, String $amount, String $reason = '')
    {
        $this->status = $status;
        $this->amount = $amount;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if (self::REJECT == $this->status)
            return ['mail', 'database'];
        else return [];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mailable = (new MailMessage)->subject(config('app.name').' Withdrawal Updates')
                    ->greeting('Hi '.$notifiable->first_name.',')
                    ->line('');
        if (self::ACCEPT == $this->status) {
            $mailable->line('Your withdrawal of '.$this->amount.' has been processed successfully.');
        } else if (self::REJECT == $this->status) {
            $mailable->line('Your withdrawal of '.$this->amount.' could not be processed.')
                ->line($this->reason);
        } else if (self::PROCESS == $this->status) {
            $mailable->line('Your withdrawal of '.$this->amount.' is been processed.');
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
            'message' => '',
            'description' => '',
            'url' => 'javascript:;'
        ];
        if (self::ACCEPT == $this->status) {
            $data['message'] = 'Your withdrawal of '.$this->amount.' has been approved.';
        } else if (self::REJECT == $this->status) {
            $data['message'] = 'Your withdrawal of '.$this->amount.' could not be processed.';
            $data['description'] = $this->reason;
        } else if (self::PROCESS == $this->status) {
            $data['message'] = 'Your withdrawal of '.$this->amount.' is been processed.';
        }
        return $data;
    }
}
