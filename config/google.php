<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Google configuration
    |--------------------------------------------------------------------------
    |
    | Here you will find configurations for the Google Api's.
    |
    */

    'authorization' => [
        'path' => sprintf('%s/%s', storage_path(), env('GOOGLE_VISION_AUTH_KEY_PATH') ?? 'path/to/key.json'),
    ],

];
