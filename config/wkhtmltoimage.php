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
    'width' => 0,

    /*
    |--------------------------------------------------------------------------
    | Javascript delay <msec>
    |--------------------------------------------------------------------------
    |
    | Wait some milliseconds for javascript finish
    |
    */
    'javascript-delay' => 700,

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
    'ignoreWarnings' => true,

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
    ]
];