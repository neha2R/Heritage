<?php

namespace App\Notifications;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class RealTimeNotification extends Notification implements ShouldBroadcast
{

    public string $message;
    public string $id2;

    public function __construct(string $message,string $id2)
    {
        $this->message = $message;
        $this->id2 = $id2;
    }
    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.User.' . $this->id2);
    }
    public function via($notifiable): array
    {
        return ['broadcast'];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => "$this->message (User '$notifiable->id')"
        ]);
    }
}
