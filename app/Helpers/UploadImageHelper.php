<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class UploadImageHelper
{
  public function upload($file, string $module = '')
  {
    $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    $fileExtension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
    $newFileName = hash('sha256', $fileName . time()) . '.' . $fileExtension;
    // up ảnh
    $path = $file->storeAs("images/{$module}", $newFileName, 'public');

    return $path;
  }

  public function delete(string $path, $disk = 'public'): bool
  {
    // Xóa file trong storage/app/public
    return Storage::disk($disk)->delete($path);
  }
}
