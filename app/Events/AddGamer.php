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

class AddGamer implements ShouldBroadcastNow{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $attraction_id;
    public $gamer;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data) {
        $this->attraction_id = $data[0];
        $this->gamer = $data[1];
    }

    /**
     * Имя канала по которому транслируется событие
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() {
        return ['attraction'];
    }

    // Имя события
    public function broadcastAs() {
        return 'add-gamer_'.$this->attraction_id;
    }

    // приходит в событии
    public function broadcastWith() {
        return [
            'gamer' => $this->gamer,
        ];
    }



}
