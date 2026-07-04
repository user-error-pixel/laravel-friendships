<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Friendship Tables
    |--------------------------------------------------------------------------
    |
    | These table names are used by the package to store friendship data.
    | You can change these values if your application uses different table names.
    |
    */

    'tables' => [

        // Stores the main friendship relationships between users.
        'fr_pivot' => 'friendships',

        // Stores which friendship groups a user has assigned to their friends.
        'fr_groups_pivot' => 'user_friendship_groups',
    ],

    /*
    |--------------------------------------------------------------------------
    | Friendship Groups
    |--------------------------------------------------------------------------
    |
    | These are the default friendship groups available in the package.
    | The numeric values are stored in the database, so avoid changing them
    | after users have already started using groups.
    |
    */

    'groups' => [

        // General friends or people the user knows casually.
        'acquaintances' => 0,

        // Friends the user considers closer than normal acquaintances.
        'close_friends' => 1,

        // Friends marked as family members.
        'family' => 2,
    ],

];