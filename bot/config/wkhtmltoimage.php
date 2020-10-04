<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Full path to the wkhtmltoimage command.
    |--------------------------------------------------------------------------
    |
    | Default is wkhtmltoimage which assumes that the command is in your shell's search path.
    |
    */
    'binary' => env("WK_HTML_TO_IMAGE_BINARY", 'wkhtmltoimage'),

    /*
    |--------------------------------------------------------------------------
    | Screen width <int>
    |--------------------------------------------------------------------------
    |
    | Set screen width, note that this is used only as a guide line. Use --disable-smart-width to make it strict.
    |
    */
    'width' => env("WK_HTML_TO_IMAGE_WIDTH", 0),

    /*
    |--------------------------------------------------------------------------
    | Javascript delay <msec>
    |--------------------------------------------------------------------------
    |
    | Wait some milliseconds for javascript finish
    |
    */
    'javascript-delay' => env("WK_HTML_TO_IMAGE_JAVASCRIPT_DELAY", 700),

    /*
    |--------------------------------------------------------------------------
    | Enable javascript
    |--------------------------------------------------------------------------
    |
    | Do allow web pages to run javascript
    |
    */
    'enable-javascript',

    /*
    |--------------------------------------------------------------------------
    | Debug javascript
    |--------------------------------------------------------------------------
    |
    | Show javascript debugging output
    |
    */
    'debug-javascript',

    /*
    |--------------------------------------------------------------------------
    | No stop slow scripts
    |--------------------------------------------------------------------------
    |
    | Do not Stop slow running javascripts
    |
    */
    'no-stop-slow-scripts',

    /*
    |--------------------------------------------------------------------------
    | Transparent
    |--------------------------------------------------------------------------
    |
    | For transparent background
    |
    */
    'transparent',

    /*
    |--------------------------------------------------------------------------
    | Enable smart width
    |--------------------------------------------------------------------------
    |
    | Enable smart width
    |
    */
    'enable-smart-width',

    /*
    |--------------------------------------------------------------------------
    | Ignore warnings <bool>
    |--------------------------------------------------------------------------
    |
    | Ignore warnings
    |
    */
    'ignoreWarnings' => env("WK_HTML_TO_IMAGE_IGNORE_WARNINGS", true),

    /*
    |--------------------------------------------------------------------------
    | Zoom <float>
    |--------------------------------------------------------------------------
    |
    | Use this zoom factor
    |
    */
    'zoom' => env('WK_HTML_TO_IMAGE_ZOOM', 1.0),

    /*
    |--------------------------------------------------------------------------
    | Command options
    |--------------------------------------------------------------------------
    |
    | List of command options
    |
    */
    'commandOptions' => [
        'useExec' => true // Can help on Windows systems
    ],
];