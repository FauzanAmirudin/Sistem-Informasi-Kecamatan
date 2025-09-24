<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FileHelper
{
    /**
     * Generate URL untuk file yang disimpan di storage
     * 
     * @param string|null $filePath
     * @return string|null
     */
    public static function getFileUrl($filePath)
    {
        if (!$filePath) {
            return null;
        }

        // Pastikan file ada di storage
        if (!Storage::disk('uploads')->exists($filePath)) {
            return null;
        }

        return asset('uploads/' . $filePath);
    }

    /**
     * Check apakah file ada di storage
     * 
     * @param string|null $filePath
     * @return bool
     */
    public static function fileExists($filePath)
    {
        if (!$filePath) {
            return false;
        }

        return Storage::disk('uploads')->exists($filePath);
    }

    /**
     * Generate URL untuk preview file (PDF, Image)
     * 
     * @param string|null $filePath
     * @return string|null
     */
    public static function getPreviewUrl($filePath)
    {
        return self::getFileUrl($filePath);
    }

    /**
     * Generate URL untuk download file
     * 
     * @param string|null $filePath
     * @return string|null
     */
    public static function getDownloadUrl($filePath)
    {
        return self::getFileUrl($filePath);
    }
}
