<?php

return [
    'show_warnings' => false,
    'orientation' => 'portrait',
    'defines' => [
        'DOMPDF_FONT_DIR' => storage_path('fonts/'),
        'DOMPDF_FONT_CACHE' => storage_path('fonts/'),
        'DOMPDF_TEMP_DIR' => storage_path('temp/'),
        'DOMPDF_CHROOT' => base_path(),
        'DOMPDF_ENABLE_REMOTE' => true,
        'DOMPDF_ENABLE_CSS_FLOAT' => true,
        'DOMPDF_ENABLE_JAVASCRIPT' => true,
        'DOMPDF_ENABLE_HTML5PARSER' => true,
    ],
];
