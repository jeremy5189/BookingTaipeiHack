<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;
use App\User as User;
use App\Vote as Vote;
use App\Hotel as Hotel;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($x = 0; $x <= 10; $x++) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->email,
            ]);
        }
        $users = User::pluck('id')->all();
        foreach(range(1,10) as $index){
            DB::table('votes')->insert([
                'reaction' =>  $faker->biasedNumberBetween($min = 1, $max = 5, $function = 'sqrt'),
                'author' => $faker->randomElement($users),
            ]); 
        }
        $votes = Vote::pluck('id')->all();
        foreach(range(1,10) as $index){
              DB::table('hotels')->insert([
                'id' => $faker->unique()->md5(),
                'checkIn' => date('Y-m-d', strtotime("+5 days")),
                'checkOut' => date('Y-m-d', strtotime("+10 days")),
                'location' => "Taipei",
                'note' => "test note",
                'vote' => $faker->randomElement($votes),
            ]);
            
        }
         $hotels = Hotel::pluck('id')->all();
         foreach(range(1,10) as $index){
             DB::table('polls')->insert([
                    'id' => $faker->unique()->md5(),
                    'title' => "Taipei trip".$x,
                    
                    'startDate' =>  date('Y-m-d', strtotime("+5 days")),
                    'endDate' =>  date('Y-m-d', strtotime("+15 days")),
                    'personAmount' => $faker->biasedNumberBetween($min = 1, $max = 10, $function = 'sqrt'),
                    'author' => $faker->randomElement($users),
                    'hotel' => $faker->randomElement($hotels)
                ]);
         }
         
       
         
    }
}
