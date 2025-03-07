<?php

/**
 * This file returns the following structured array containing all system
 * configuration parameters including database credentials, feature flags,
 * and environment settings.
 */
return [
    // The database configuration. In Phase 2, we run the application in our
    // computers, so we would only access to the localhost database. The
    // username is "root" and the password is empty.
    'database' => [
        'host' => 'localhost',
        'dbname' => 'db2',
        'username' => 'root',
        'password' => '',
    ]
];
