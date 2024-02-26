<?php

namespace Database\Seeders;

use App\Facades\MyFunctions;
use App\Repositories\AttractionRepository;
use App\Repositories\SessionRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $attractions = (new AttractionRepository())->get();

        foreach ($attractions as $key => $arr){
            (new SessionRepository())->create([
                'attraction_id' => $arr->id,
                'mode_id' => 3,
                'session_token' => MyFunctions::generateStr(50),
                'player_max_count' => 3,
            ]);
        }
    }
}

