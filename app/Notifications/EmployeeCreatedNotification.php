<?php

namespace App\Notifications;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeeCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(public readonly Employee $employee) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Employee Added')
            ->line('A new employee "'.$this->employee->full_name.'" has been added.')
            ->line('Employee Number: '.($this->employee->employee_number ?? 'N/A'))
            ->action('View Employee', url('/admin/employees/'.$this->employee->id));
    }
}
