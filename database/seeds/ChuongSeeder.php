<?php

use App\Chuong;
use Illuminate\Database\Seeder;

class ChuongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'tieu_de' => 'Chuong 1: abc',
                'mo_ta_ngan' => 'Mo ta chuong 1.',
                'id_bai_giang' => 1,
            ],
            [
                'tieu_de' => 'Chuong 2: xyz',
                'mo_ta_ngan' => 'Mo ta chuong 2.',
                'id_bai_giang' => 2,
            ],
        ];

        foreach ($data as $item) {
            Chuong::create($item);
        }
    }
}
