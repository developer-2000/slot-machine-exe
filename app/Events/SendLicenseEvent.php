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


class SendLicenseEvent implements ShouldBroadcastNow{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $location_id;
    public $status;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data) {
        $this->location_id = $data[0];
        $this->status = $data[1];
    }

    /**
     * Имя канала по которому транслируется событие
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() {
        return ['license'];
    }

    // Имя события
    public function broadcastAs() {
        return 'license-status_'.$this->location_id;
    }

    // приходит в событии
    public function broadcastWith() {
        return [
            'attraction_id' => $this->location_id,
            'status' => $this->status,
        ];
    }
}
