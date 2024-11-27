<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    /**
     * Get the notification's mail representation.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verify Your Email Address') // Custom subject
            ->line('Thank you for registering! Please verify your email address by clicking the button below.')
            ->action('Verify Email Address', $this->verificationUrl($notifiable)) // Verification URL
            ->line('If you did not create an account, no further action is required.')
            ->salutation('Best regards, Blog'); // Custom salutation
    }
}
