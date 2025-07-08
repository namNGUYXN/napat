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
                'slug' => Str::slug('HTML Cơ bản') . '-' . Str::random(5),
                'noi_dung' => '<p>Nội dung bài HTML...</p>',
                'id_chuong' => 1,
                'thu_tu' => 1,
            ],
            [
                'tieu_de' => 'Giới thiệu Laravel',
                'slug' => Str::slug('Giới thiệu Laravel'),
                'noi_dung' => '<p>Laravel là một PHP framework mạnh mẽ...</p>',
                'id_chuong' => 2,
                'thu_tu' => 1,
            ],
            [
                'tieu_de' => 'Bai demo tu chuong 2 lop 1',
                'slug' => Str::slug('Bai demo tu chuong 2 lop 1'),
                'noi_dung' => '<p style="text-align: left;"><span style="color: #b96ad9;"><strong>Ch&uacute;ng ta h&atilde;y c&ugrave;ng U I I A n&agrave;o ~~</strong></span></p>
<p style="text-align: center;"><iframe style="width: 757px; height: 426px;" title="YouTube video player" src="https://www.youtube.com/embed/IxX_QHay02M?si=8Ro8EoRMCiuD46Jx" width="757" height="426" frameborder="0" allowfullscreen="allowfullscreen"></iframe></p>
<p style="text-align: center;"><img src="/storage/photos/2/cat_side_eye.png" alt="" width="507" height="507" /></p>',
                'id_chuong' => 3,
                'thu_tu' => 2,
            ],
            [
                'tieu_de' => 'bai 2',
                'slug' => Str::slug('bai 2') . '-' . Str::random(5),
                'noi_dung' => '<p>gggg</p>',
                'id_chuong' => 1,
                'thu_tu' => 2,
            ],
            [
                'tieu_de' => 'qqqqqq',
                'slug' => Str::slug('qqqqqq') . '-' . Str::random(5),
                'noi_dung' => '<p>sasasasas</p>',
                'id_chuong' => 4,
                'thu_tu' => 1,
            ],
            [
                'tieu_de' => 'hahaha',
                'slug' => Str::slug('hahaha') . '-' . Str::random(5),
                'noi_dung' => '<p style="text-align: center;"><strong><em>C&aacute;c bạn nghe b&agrave;i n&agrave;y thư giản nh&eacute;!!</em></strong></p>
<p style="text-align: center;"><iframe style="width: 844px; height: 475px;" title="YouTube video player" src="https://www.youtube.com/embed/r5SS17LTfr8?si=IGB3xbR0A-HN_dvd" width="956" height="538" frameborder="0" allowfullscreen="allowfullscreen"></iframe></p>',
                'id_chuong' => 3,
                'thu_tu' => 1,
            ]
        ];

        foreach ($data as $item) {
            Bai::create($item);
        }
    }
}
