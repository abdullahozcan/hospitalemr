<?php

return [
    /*
    |--------------------------------------------------------------------------
    | THS (Türkiye Sağlık Sistemi) Entegrasyon Ayarları
    |--------------------------------------------------------------------------
    |
    | driver: mock (geliştirme, gerçekçi yanıtlar) veya soap (canlı ortam,
    | kurum sertifikası ve THS kimlik bilgileri gerektirir).
    |
    */

    'enabled' => env('THS_ENABLED', true),

    'driver' => env('THS_DRIVER', 'mock'),

    /*
    | Kurum kimlik bilgileri (canlı ortam için).
    */
    'institution' => [
        'code' => env('THS_INSTITUTION_CODE'),
        'username' => env('THS_USERNAME'),
        'password' => env('THS_PASSWORD'),
        'certificate_path' => env('THS_CERTIFICATE_PATH'),
        'certificate_password' => env('THS_CERTIFICATE_PASSWORD'),
        'endpoints' => [
            'enabiz' => env('THS_ENABIZ_ENDPOINT'),
            'sgk' => env('THS_SGK_ENDPOINT'),
        ],
    ],

    /*
    | Gönderimler kuyrukta yeniden denenir.
    */
    'queue' => [
        'tries' => 3,
        'backoff_seconds' => [60, 300, 900],
    ],

    /*
    | Mock davranışı: veri eksikliğinde gerçekçi hata yanıtı döner.
    */
    'mock' => [
        'simulate_delay' => false,
    ],
];
