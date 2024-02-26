<?php

namespace App\Events;

use App\Models\Test;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class SessionStatusEvent implements ShouldBroadcastNow{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $session_id;
    public $status;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data) {
        $this->session_id = $data[0];
        $this->status = $data[1];
    }

    /**
     * Имя канала по которому транслируется событие
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() {
        return ['session-status'];
    }

    // Имя события
    public function broadcastAs() {
        return 'session-status_'.$this->session_id;
    }

    // приходит в событии
    public function broadcastWith() {
        return [
            'session_id' => $this->session_id,
            'status' => $this->status,
        ];
    }
}
