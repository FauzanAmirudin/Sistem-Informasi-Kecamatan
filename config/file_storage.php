<?php

return [
    /*
    |--------------------------------------------------------------------------
    | File Storage Configuration for Production
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk memastikan file storage berfungsi dengan benar
    | di production environment
    |
    */

    'storage' => [
        'disk' => env('FILESYSTEM_DISK', 'public'),
        'public_url' => env('APP_URL') . '/storage',
        'ensure_symlink' => true,
        'permissions' => [
            'storage' => 0755,
            'public_storage' => 0755,
        ],
    ],

    'upload' => [
        'max_size' => 2048, // KB
        'allowed_types' => [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ],
    ],

    'directories' => [
        'dokumen' => 'dokumen',
        'aset_tanah_warga' => 'aset-tanah-warga',
        'aset_desa' => 'bukti-aset',
        'perangkat_desa' => 'sk-perangkat',
        'kepala_desa' => 'sk-kepala-desa',
        'monografi' => 'monografi',
        'profile_photos' => 'profile-photos',
    ],
];
