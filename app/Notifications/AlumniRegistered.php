<?php
namespace App\Notifications;

use App\Models\Alumni;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlumniRegistered extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Alumni $alumni,
        public string $registrationMethod
    ) {}

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $subject = $this->registrationMethod === 'sis' 
            ? 'Welcome to STU Alumni Network!'
            : 'STU Alumni Registration Submitted';

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($this->getMessage())
            ->action('Visit Your Dashboard', url('/alumni/dashboard'))
            ->line('Thank you for joining the STU Alumni Network!');
    }

    public function toArray($notifiable)
    {
        return [
            'alumni_id' => $this->alumni->id,
            'alumni_name' => $this->alumni->full_name,
            'registration_method' => $this->registrationMethod,
            'verification_status' => $this->alumni->verification_status,
            'message' => $this->getMessage(),
        ];
    }

    private function getMessage(): string
    {
        if ($this->registrationMethod === 'sis') {
            return 'Your STU Alumni account has been successfully created and verified. You can now access all alumni features.';
        }

        return 'Your STU Alumni registration has been submitted for verification. You will be notified once your account is approved.';
    }
}
