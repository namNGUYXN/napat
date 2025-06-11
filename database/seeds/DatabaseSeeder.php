<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        $this->call(NguoiDungSeeder::class);
        $this->call(LoaiMenuSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(KhoaSeeder::class);
        $this->call(HocPhanSeeder::class);
    }
}
