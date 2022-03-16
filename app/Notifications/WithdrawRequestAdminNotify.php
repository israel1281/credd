<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawRequestAdminNotify extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $user;
    public $amount;
    public function __construct(User $user, String $amount)
    {
        $this->user = $user;
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
        return (new MailMessage)->subject('New Withdrawal Request')
                    ->line('You have a new withdrawal request from '.$this->user->name)
                    ->line('Amount: '.$this->amount)
                    ->action('Go to Withdrawal Requests', spaUrlBuilder('/dashboard/admin/withdraw/pending'));
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
            'title' => 'Withdrawal Request',
            'message' => 'New withdrawal request from '.$this->user->name,
            'url' => spaUrlBuilder('/dashboard/admin/withdraw/pending')
        ];
    }
}
