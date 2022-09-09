<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DatabaseNotification extends Notification
{
    use Queueable;
    public $subject; //subject of the message
    public $message; //message
    public $href;    // url
    public $hrefText; //url text eg. read more, view
    public $owner = null;
    public $hrefParameters = null; //parameters

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($subject = null, $message, $href,$hrefText='view',$hrefParameters=null,  $owner=null)
    {
        $this->subject = $subject;    //the subject
        $this->message = $message;    //your notification message
        $this->href = $href;          //url
        $this->hrefText = $hrefText;    //url text eg. read more, view 
        $this->hrefParameters = $hrefParameters ?? null;   //parameters
        $this->owner = $owner ?? null;  //owner
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

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ($this->owner == 'taxpayer') {
            return (new MailMessage)
                ->subject($this->subject)
                ->greeting('Hello! ' . $notifiable->full_name)
                ->line($this->message)
                ->line('Kindly login to the application to view the details')
                ->line('Thank you!');
        } else {
            return (new MailMessage)
                ->subject($this->subject)
                ->greeting('Hello! ' . $notifiable->full_name)
                ->line($this->message)
                ->action('Kindly click here to approve', $this->hrefParameters != null ? route($this->href,$this->hrefParameters) : route($this->href))
                ->line('Thank you!');
        }
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
            'subject'         => $this->subject,
            'message'         => $this->message,
            'href'            => $this->href,
            'hrefText'        => $this->hrefText,
            'hrefParameters'  => $this->hrefParameters,
        ];
    }
}
