<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;

class ImageUploadService
{
    private Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('services.cloudinary.cloud_name'),
                'api_key'    => config('services.cloudinary.api_key'),
                'api_secret' => config('services.cloudinary.api_secret'),
            ],
            'url' => ['secure' => true],
        ]);
    }

    public function upload(UploadedFile $file, string $folder = 'litoral-hogar'): array
    {
        $result = $this->cloudinary->uploadApi()->upload(
            $file->getRealPath(),
            [
                'folder'         => $folder,
                'transformation' => ['width' => 400, 'height' => 400, 'crop' => 'fill'],
            ]
        );

        return [
            'secure_url' => $result['secure_url'],
            'public_id'  => $result['public_id'],
        ];
    }
}
