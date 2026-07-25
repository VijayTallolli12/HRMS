<?php

namespace App\Notifications;

use App\Models\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrganizationCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly Organization $organization) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Organization Created')
            ->line('A new organization "'.$this->organization->name.'" has been created.')
            ->line('Legal Name: '.($this->organization->legal_name ?? 'N/A'))
            ->action('View Organization', url('/admin/organizations/'.$this->organization->id));
    }
}
