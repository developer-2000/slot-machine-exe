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

class ResponseAddGamer implements ShouldBroadcastNow{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bool;
    public $gamer_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data) {
        $this->gamer_id = $data[0];
        $this->bool = $data[1];
    }

    /**
     * Имя канала по которому транслируется событие
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() {
        return ['response_add_gamer'];
    }

    // Имя события
    public function broadcastAs() {
        return 'response_add_gamer_'.$this->gamer_id;
    }

    // приходит в событии
    public function broadcastWith() {
        return [
            'user_is_added' => $this->bool,
        ];
    }
}
