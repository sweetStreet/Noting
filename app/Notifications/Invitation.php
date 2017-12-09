<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Invitation extends Notification
{
    use Queueable;
    public $from_user_email;
    public $from_user_name;
    public $content;



    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($from_user_email,$from_user_name, $content)
    {
        $this->from_user_email = $from_user_email;
        $this->from_user_name = $from_user_name;
        $this->content = $content;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'from_user_email' => $this->from_user_email,
            'from_user_name' => $this->from_user_name,
            'content' => $this->content
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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

        ];
    }
}
