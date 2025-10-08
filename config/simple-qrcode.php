<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default QR Code Writer
    |--------------------------------------------------------------------------
    |
    | This option controls the default QR code writer that will be used
    | by the SimpleSoftwareIO QrCode package. We're explicitly setting
    | it to use the GD backend to avoid Imagick dependency issues.
    |
    */
    'writer' => 'png',
    
    /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    |
    | Default settings for QR code generation
    |
    */
    'size' => 300,
    'margin' => 2,
    'format' => 'png',
    'encoding' => 'UTF-8',
    'error_correction' => 'M',
    'foreground_color' => [0, 0, 0],
    'background_color' => [255, 255, 255],
    
    /*
    |--------------------------------------------------------------------------
    | Force GD Backend
    |--------------------------------------------------------------------------
    |
    | Force the use of GD backend instead of Imagick
    |
    */
    'backend' => 'gd',
];