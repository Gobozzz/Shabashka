<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Carbon\Carbon;

class FileService
{

    public function downloadImage(UploadedFile $file, string $dir = "uploads", array $scale = []): array|null
    {
        try {
            $image = Image::read($file);
            if ($scale) {
                $image->scaleDown($scale['width'] ?? null, $scale['height'] ?? null);
            }
            $currentYearMonth = Carbon::now()->format('Y-m');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = "$dir/$currentYearMonth/$filename";
            Storage::put(
                "/$path",
                $image->encodeByExtension($file->getClientOriginalExtension(), quality: 50)
            );
            return ['filename' => $filename, 'path' => $path];
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function downloadImages(array $images, string $dir = "uploads", array $scale = []): array
    {
        $result = [];
        foreach ($images as $image) {
            $downloadImageData = $this->downloadImage($image, $dir, $scale);
            if ($downloadImageData !== null) {
                array_push($result, $downloadImageData);
            }
        }
        return $result;
    }

    public function removeFile(string $pathInStorage)
    {
        if (Storage::exists($pathInStorage)) {
            Storage::delete($pathInStorage);
        }
    }

}