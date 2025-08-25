<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Models\Voter;

class NewVoterRegisteredNotification extends Notification
{
    use Queueable;

    public $voter;

    /**
     * Create a new notification instance.
     */
    public function __construct(Voter $voter)
    {
        $this->voter = $voter;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'voter_id' => $this->voter->id,
            'voter_name' => $this->voter->name,
            'message' => 'New voter registered: ' . $this->voter->name,
            'link' => route('admin.voters.show', $this->voter->id), // Assuming a route for voter details
        ];
    }

    /**
     * Get the database representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }
}
