<?php

/**
 * This file returns the following structured array containing all system
 * configuration parameters including database credentials, feature flags,
 * and environment settings.
 *
 * @access private This file is accessible only to server-side code and is
 *         protected from client-side requests through server security measures.
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
