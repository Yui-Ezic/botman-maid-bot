<?php

return [
    'dictionaries' => [
        'rus' => [
            'blacklist' => resource_path('dictionaries\rus\blacklist.php'),
            'whitelist' => require resource_path('dictionaries\rus\whitelist.php')
        ]
    ]
];