<?php

use Kevindierkx\Api\Exceptions\Formats\GenericFormat;

return [

    /*
    |--------------------------------------------------------------------------
    | Error Format
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    'exceptions' => [
        'formats' => [
            // 'name' => Class::class,
        ],
        'default' => GenericFormat::class,
    ],

];
