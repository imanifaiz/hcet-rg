<?php

namespace App\Notifications;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEmployeeAdded extends Notification
{
    use Queueable;

    public function __construct(private Employee $employee)
    {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->markdown(
            'mail.company.new-employee-added',
            ['employee' => $this->employee]
        );
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
