<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('users')->insert([
		'name' => 'Arun',
		'email' => 'arun@smartdesizns.co.in',
		'password' => Hash::make('123456')
		]);
    }
}
