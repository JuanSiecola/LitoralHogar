<?php
namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;

class ImageUploadService
{
    private Cloudinary $cloudinary;

    private const PROFILES = [
        // Logos y avatares: cuadrado, recortado, liviano
        'avatar' => [
            'width' => 400, 'height' => 400, 'crop' => 'fill',
            'gravity' => 'face', 'quality' => 'auto', 'fetch_format' => 'auto',
        ],
        // Propiedades: alta resolución, sin recortar, calidad alta
        'propiedad' => [
            'width' => 1600, 'crop' => 'limit',
            'quality' => 'auto:good', 'fetch_format' => 'auto',
        ],
    ];

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

    public function upload(
        UploadedFile $file,
        string $folder = 'litoral-hogar',
        string $profile = 'avatar'
    ): array {
        $result = $this->cloudinary->uploadApi()->upload(
            $file->getRealPath(),
            [
                'folder'         => $folder,
                'transformation' => self::PROFILES[$profile] ?? self::PROFILES['avatar'],
            ]
        );

        return [
            'secure_url' => $result['secure_url'],
            'public_id'  => $result['public_id'],
        ];
    }

    public function delete(string $publicId): void
    {
        $this->cloudinary->uploadApi()->destroy($publicId);
    }
}