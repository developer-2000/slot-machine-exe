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

class QueryAddGamer implements ShouldBroadcastNow{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $session_id;
    public $gamer_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data) {
        $this->session_id = $data[0];
        $this->gamer_id = $data[1];
    }

    /**
     * Имя канала по которому транслируется событие
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() {
        return ['query_add_gamer'];
    }

    // Имя события
    public function broadcastAs() {
        return 'query_add_gamer_'.$this->session_id;
    }

    // приходит в событии
    public function broadcastWith() {
        return [
            'gamer_id' => $this->gamer_id,
            'session_id' => $this->session_id,
        ];
    }
}
