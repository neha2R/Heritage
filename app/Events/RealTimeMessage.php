<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RealTimeMessage implements ShouldBroadcast
{
    use Dispatchable,InteractsWithSockets,SerializesModels;

    private  $message;
    private  $to_user_id;
    private  $from_user_id;
    public function __construct($message,$to_user_id,$from_user_id)
    {
        $this->message = $message;
        $this->to_user_id = $to_user_id;
        $this->from_user_id = $from_user_id;
     //  ConsoleOutput::writeln($id2);

    }
    public function broadcastWith()
    {
        return ['chat' => $this->message,'to_user_id' => $this->to_user_id,'from_user_id' => $this->from_user_id];
    }
    public function broadcastAs()
    {
        return 'getmessages';
    }
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('events');
    }
}
