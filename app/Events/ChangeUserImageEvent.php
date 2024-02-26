<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChangeUserImageEvent implements ShouldBroadcastNow{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $session_id;
    public $gamer_id;
    public $select_border;
    public $select_avatar;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data) {
        $this->session_id = $data[0];
        $this->gamer_id = $data[1];
        $this->select_border = $data[2];
        $this->select_avatar = $data[3];
    }

    /**
     * Имя канала по которому транслируется событие
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() {
        return ['change_image_gamer'];
    }

    // Имя события
    public function broadcastAs() {
        return 'change_image_gamer_'.$this->session_id;
    }

    // приходит в событии
    public function broadcastWith() {
        return [
            'gamer_id' => $this->gamer_id,
            'select_border' => $this->select_border,
            'select_avatar' => $this->select_avatar,
        ];
    }
}
