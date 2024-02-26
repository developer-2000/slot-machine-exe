<?php

namespace Database\Seeders;

use App\Repositories\AttractionRepository;
use App\Repositories\LocationRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttractionSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $locations = (new LocationRepository())->get();

        foreach ($locations as $key => $arr){
            (new AttractionRepository())->create([
                'user_id' => $arr->user_id,
                'title' => "Аттракцион{$key}",
                'location_id' => $arr->id,
            ]);
        }
    }
}
