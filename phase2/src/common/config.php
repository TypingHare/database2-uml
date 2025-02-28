<?php

/**
 * Retrieves configuration settings from a specified configuration file.
 *
 * @param string $name The name of the configuration file (without extension).
 * @return array The configuration settings as an associative array.
 * @throws InvalidArgumentException If the configuration file does not exist.
 * @throws RuntimeException If the configuration file does not return a valid
 * array.
 */
function get_config(string $name): array
{
    $filePath = __DIR__ . "/../config/$name.config.php";
    if (!file_exists($filePath)) {
        throw new InvalidArgumentException(
            "Configuration file '$name.config.php' does not exist."
        );
    }

    $config = require $filePath;

    if (!is_array($config)) {
        throw new RuntimeException(
            "Configuration file '$name.config.php' must return an array."
        );
    }

    return $config;
}
