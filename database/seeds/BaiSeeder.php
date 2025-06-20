<?php
use App\Bai;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'tieu_de' => 'HTML Cơ bản',
                'slug' => Str::slug('HTML Cơ bản'),
                'noi_dung' => 'Nội dung bài HTML...',
                'id_chuong' => 1,
                'is_delete' => false,
            ],
            [
                'tieu_de' => 'Giới thiệu Laravel',
                'slug' => Str::slug('Giới thiệu Laravel'),
                'noi_dung' => 'Laravel là một PHP framework mạnh mẽ...',
                'id_chuong' => 2,
                'is_delete' => false,
            ],
        ];

        foreach ($data as $item) {
            Bai::create($item);
        }
    }
}
