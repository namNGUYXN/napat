<?php

namespace App\Imports;

use App\NguoiDung;
use App\ThanhVienLop;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ThanhVienLopImport implements OnEachRow, WithHeadingRow
{
    protected $idLopHocPhan;

    public function __construct($idLopHocPhan)
    {
        $this->idLopHocPhan = $idLopHocPhan;
    }

    public function onRow(Row $row)
    {
        $data = $row->toArray();

        $sinhVien = NguoiDung::where('email', $data['email'])->first();

        if ($sinhVien) {
            $thanhVienLop = ThanhVienLop::where([
                ['id_lop_hoc_phan', $this->idLopHocPhan],
                ['id_nguoi_dung', $sinhVien->id],
            ]);

            $tontai = $thanhVienLop->exists();
            $thanhVienLop = $thanhVienLop->first();

            if (!$tontai) {
                ThanhVienLop::create([
                    'id_lop_hoc_phan' => $this->idLopHocPhan,
                    'id_nguoi_dung' => $sinhVien->id,
                    'is_accept' => true
                ]);
            } else if ($tontai && !$thanhVienLop->is_accept) {
                $thanhVienLop->is_accept = true;
                $thanhVienLop->save();
            }
        } else {
            // Log::warning("Email không tồn tại: " . $data['email']);
        }
    }
}
