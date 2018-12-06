<?php
return [
    'image' => [
        'directory' => 'img/app',
        'thumbnail' => [
            'width' => 250,
            'height' => 170
        ]
    ],
    #---- the default_category_id which name is Uncategorized and id is 1, will be protected, and o matter what you cant delete it
    'default_category_id' => 1,
    'default_user_id' => 1,
];
