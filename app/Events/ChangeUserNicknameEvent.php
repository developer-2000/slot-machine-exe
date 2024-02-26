<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChangeUserNicknameEvent implements ShouldBroadcastNow{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $session_id;
    public $user_id;
    public $nickname;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data) {
        $this->session_id = $data[0];
        $this->user_id = $data[1];
        $this->nickname = $data[2];
    }

    /**
     * Имя канала по которому транслируется событие
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() {
        return ['change_user_nickname'];
    }

    // Имя события
    public function broadcastAs() {
        return 'change_user_nickname_'.$this->session_id;
    }

    // приходит в событии
    public function broadcastWith() {
        return [
            'user_id' => $this->user_id,
            'nickname' => $this->nickname,
        ];
    }
}
