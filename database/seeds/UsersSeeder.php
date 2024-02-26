<?php
namespace Database\Seeders;

use App\Models\License;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\UsersRoles;

class UsersSeeder extends Seeder {

    /**
     * Run the database seeds.
     * @return void
     */
    public function run() {
        $data = [
            [
                'Root',
                'root@root.root',
                'rootroot',
                'Саша',
                '{"code":"CA","name":"Canada"}',
                '{"code":5883102,"name":"Alberta"}',
                '{"code":5913490,"geonamesCode":5913490,"name":"Calgary","latitude":51.05011,"longitude":-114.08529,"population":1019942}',
                '2000-02-03'
            ],
            [
                'Admin', 'admin@admin.admin', 'adminadmin', 'Иван', '{"code":"CA","name":"Canada"}', '{"code":5883102,"name":"Alberta"}', '{"code":5913490,"geonamesCode":5913490,"name":"Calgary","latitude":51.05011,"longitude":-114.08529,"population":1019942}', '2000-02-03'
            ],
            [
                'Gamer', 'gamer@gamer.gamer', 'gamergamer', 'Рома', '{"code":"CA","name":"Canada"}', '{"code":5883102,"name":"Alberta"}', '{"code":5913490,"geonamesCode":5913490,"name":"Calgary","latitude":51.05011,"longitude":-114.08529,"population":1019942}', '2000-02-03'
            ],
            [
                'Root2', 'root2@root2.root', 'root2root2', 'Валентин', '{"code":"CA","name":"Canada"}', '{"code":5883102,"name":"Alberta"}', '{"code":5913490,"geonamesCode":5913490,"name":"Calgary","latitude":51.05011,"longitude":-114.08529,"population":1019942}', '2000-02-03'
            ],
            [
                'Admin2', 'admin2@admin2.admin', 'admin2admin2', 'Гоша', '{"code":"CA","name":"Canada"}', '{"code":5883102,"name":"Alberta"}', '{"code":5913490,"geonamesCode":5913490,"name":"Calgary","latitude":51.05011,"longitude":-114.08529,"population":1019942}', '2000-02-03'
            ],
            [
                'Gamer2', 'gamer2@gamer2.gamer', 'gamer2gamer2', 'Алеша', '{"code":"CA","name":"Canada"}', '{"code":5883102,"name":"Alberta"}', '{"code":5913490,"geonamesCode":5913490,"name":"Calgary","latitude":51.05011,"longitude":-114.08529,"population":1019942}', '2000-02-03'
            ],
        ];

        // ======================================
        // создать юзеров
        foreach ($data as $key => $arr){
            $user = User::create([
                'nickname' => $arr[0],
                'email' => $arr[1],
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make($arr[2]),
                'first_name' => $arr[3],
                'country' => json_decode($arr[4]),
                'region' => json_decode($arr[5]),
                'city' => json_decode($arr[6]),
                'date_of_birth' => Carbon::parse($arr[7]),
            ]);

            // связь юзера с ролью
            if( (($key+1) % 1) == 0 || (($key+1) % 4) == 0 ){
                UsersRoles::create([ 'user_id' => $user->id, 'role_id' => 1]);
            }
            elseif( (($key+1) % 2) == 0 || (($key+1) % 5) == 0){
                UsersRoles::create([ 'user_id' => $user->id, 'role_id' => 2]);
                // добавить админу локацию и лицензию
                $this->addLicense($user);
            }
            elseif( (($key+1) % 3) == 0 || (($key+1) % 6) == 0 ){
                UsersRoles::create([ 'user_id' => $user->id, 'role_id' => 3]);
            }
        }
    }

    // >>>>>
    // добавить админу локацию и лицензию
    public function addLicense($user) {
        // добавить администратору таблицу локации
        $location = Location::create([
            'user_id' => $user->id,
            'title' => 'Локация юзера id ' . $user->id,
            'country' => json_decode('{"code":"CA","name":"Canada"}'),
            'region' => json_decode('{"code":5883102,"name":"Alberta"}'),
            'city' => json_decode('{"code":5913490,"geonamesCode":5913490,"name":"Calgary","latitude":51.05011,"longitude":-114.08529,"population":1019942}'),
            'street' => 'Test улица для админа',
            'price' => 100,
        ]);

        // добавить администратору таблицу лицензии
        License::create([
            'user_id' => $user->id,
            'location_id' => $location->id,
        ]);
    }






















}
