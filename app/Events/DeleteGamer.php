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

class DeleteGamer implements ShouldBroadcastNow{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $gamer_id;
    public $delete_status;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data) {
        $this->gamer_id = $data[0];
        $this->delete_status = $data[1];
    }

    /**
     * Имя канала по которому транслируется событие
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() {
        return ['delete_status'];
    }

    // Имя события
    public function broadcastAs() {
        return 'delete_status_'.$this->gamer_id;
    }

    // приходит в событии
    public function broadcastWith() {
        return [
            'delete_status' => $this->delete_status,
        ];
    }
}
