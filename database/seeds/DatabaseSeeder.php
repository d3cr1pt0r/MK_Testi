<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Klemen',
            'surname' => 'Brajkovič',
            'email' => 'd3cr1pt0r@gmail.com',
            'password' => Hash::make('caffeine'),
        ]);
    }
}
