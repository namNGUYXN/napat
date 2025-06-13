<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class SecureFileController extends Controller
{
    public function download($id_nguoi_dung, $ten_file)
    {
        if (session('id_nguoi_dung') != $id_nguoi_dung) {
            abort(403, 'Không có quyền tải file này.');
        }

        $path = "private/files/{$id_nguoi_dung}/{$ten_file}";

        if (!Storage::exists($path)) {
            abort(404, 'Không tìm thấy file.');
        }

        // return Storage::download($path);

        $file = Storage::get($path);
        $mime = Storage::mimeType($path);

        // return response($file)->header('Content-Type', $mime);
        return response($file)
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'inline; filename="' . $ten_file . '"');
    }

    public function image($id_nguoi_dung, $ten_anh)
    {
        if (session('id_nguoi_dung') != $id_nguoi_dung) {
            abort(403, 'Không có quyền xem ảnh này.');
        }

        $path = "private/photos/{$id_nguoi_dung}/{$ten_anh}";

        if (!Storage::exists($path)) {
            abort(404, 'Không tìm thấy ảnh.');
        }

        $file = Storage::get($path);
        $mime = Storage::mimeType($path);

        return response($file)->header('Content-Type', $mime);
    }
}
