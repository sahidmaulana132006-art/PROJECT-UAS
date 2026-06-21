<?php

// Create necessary writeable folders for Laravel inside /tmp
$dirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/app/public/posters',
    '/tmp/storage/app/public/payments',
    '/tmp/storage/app/public/certificates',
    '/tmp/storage/fonts', // For DomPDF font cache
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Dynamically set writeable paths for compilation, cache, and logs on Vercel
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('LOG_CHANNEL=stderr');
putenv('DOMPDF_TEMP_DIR=/tmp');
putenv('DOMPDF_FONT_CACHE=/tmp/storage/fonts');

// Load the actual entry point
require __DIR__ . '/../public/index.php';
