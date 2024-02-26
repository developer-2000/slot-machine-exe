<?php

namespace Database\Seeders;

use App\Repositories\AttractionRepository;
use App\Repositories\SessionRepository;
use App\Repositories\SessionUserRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SessionUserSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $users = (new UserRepository())->selectLike('nickname', 'gamer');
        $sessions = (new SessionRepository())->get();

        foreach ($sessions as $key => $arr_ses){
            foreach ($users as $key2 => $arr_user) {
                (new SessionUserRepository())->create([
                    'session_id' => $arr_ses->id,
                    'user_id' => $arr_user->id,
                ]);
            }
        }
    }
}
