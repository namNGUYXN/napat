<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class SecureFileController extends Controller
{
    public function __construct()
    {
        $this->middleware('secure_file')->only('privateImage', 'privateFile', 'privateImageWord');
    }

    private function downloadFile($id_nguoi_dung, $ten_file)
    {
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

    private function browseImage($id_nguoi_dung, $ten_anh)
    {
        $path = "private/photos/{$id_nguoi_dung}/{$ten_anh}";

        if (!Storage::exists($path)) {
            abort(404, 'Không tìm thấy ảnh.');
        }

        $file = Storage::get($path);
        $mime = Storage::mimeType($path);

        return response($file)->header('Content-Type', $mime);
    }

    public function privateFile($id_nguoi_dung, $ten_file)
    {
        return $this->downloadFile($id_nguoi_dung, $ten_file);
    }

    public function privateImage($id_nguoi_dung, $ten_anh)
    {
        return $this->browseImage($id_nguoi_dung, $ten_anh);
    }

    public function publicFile($ten_file)
    {
        return $this->downloadFile('shares', $ten_file);
    }

    public function publicImage($ten_anh)
    {
        return $this->browseImage('shares', $ten_anh);
    }

    public function privateImageWord($id_nguoi_dung, $ten_thu_muc, $ten_anh)
    {
        $path = "{$id_nguoi_dung}/{$ten_thu_muc}";

        return $this->browseImage($path, $ten_anh);
    }
}
