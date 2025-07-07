<?php

namespace App\Imports;

use App\Khoa;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;

class KhoaImport implements OnEachRow, SkipsOnFailure
{
    use SkipsFailures;

    protected $validRows = [];
    protected $failures = [];

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $rowData = $row->toArray();

        $messages = [
            'ma.required' => 'Mã khoa là bắt buộc.',
            'ma.unique'   => 'Đã tồn tại khoa với Mã này rồi',
            'ten.required' => 'Tên khoa là bắt buộc.',
            'ten.unique' => 'Đã tồn tại khoa với tên này rồi',
            'ten.regex'    => 'Tên khoa chỉ được chứa chữ cái và khoảng trắng.',
            'email.unique' => 'Đã tồn tại khoa với email này rồi',
        ];

        $validator = Validator::make([
            'ma'         => $rowData[0] ?? null,
            'ten'        => $rowData[1] ?? null,
            'email'      => $rowData[2] ?? null,
            'mo_ta_ngan' => $rowData[3] ?? null,
        ], [
            'ma'         => ['required', 'unique:khoa,ma'],
            'ten'        => ['required', 'unique:khoa,ten', 'regex:/^[\p{L}\s]+$/u'],
            'email'      => ['nullable', 'unique:khoa,email'],
            'mo_ta_ngan' => ['nullable'],
        ], $messages);

        if ($validator->fails()) {
            $this->failures[] = new Failure(
                $rowIndex,
                '',
                $validator->errors()->all(),
                $rowData
            );
            return;
        }

        $this->validRows[] = [
            'ma'        => $rowData[0],
            'ten'       => $rowData[1],
            'slug' => Str::slug($rowData[2]),
            'email'     => $rowData[2],
            'mo_ta_ngan' => $rowData[3],
            'ngay_tao' => now(),
        ];
    }

    public function getValidRows()
    {
        return $this->validRows;
    }

    public function failures()
    {
        return collect($this->failures);
    }
}
