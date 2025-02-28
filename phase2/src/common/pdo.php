<?php

namespace common;

use InvalidArgumentException;
use PDO;
use ReflectionClass;

/**
 * Establishes a connection to the database using configuration parameters
 * from the database config file.
 *
 * Sets up a PDO connection with proper error handling, fetch mode, and
 * prepared statement settings.
 *
 * @return PDO
 * @see ../config/database.config.php
 * @author James Chen
 */
function connect_database(): PDO
{
    $database_config = get_config('database');
    $host = $database_config['host'];
    $dbname = $database_config['dbname'];
    $username = $database_config['username'];
    $password = $database_config['password'];

    return new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
}

/**
 * Populates a model object with data from an associative array.
 *
 * @param object $model The model object to populate.
 * @param array $record The associative array containing data. The keys are
 * in lower case.
 * @return object The populated model.
 * @throws InvalidArgumentException If a required field is missing in $record.
 * @author James Chen
 */
function populate_model(object $model, array $record): object
{
    $reflection = new ReflectionClass($model);

    foreach ($reflection->getProperties() as $property) {
        $camelCaseName = $property->getName();
        $snakeCaseName = strtolower(
            preg_replace('/([a-z])([A-Z])/', '$1_$2', $camelCaseName)
        );

        if (!array_key_exists($snakeCaseName, $record)) {
            throw new InvalidArgumentException(
                "Missing required field: $snakeCaseName"
            );
        }

        $property->setValue($model, $record[$snakeCaseName]);
    }

    return $model;
}


/**
 * Converts an array of database records into an array of model objects.
 *
 * @param iterable $records An iterable collection of associative arrays
 * representing database records.
 * @param string $modelClass The fully qualified class name of the model to
 * instantiate.
 * @return array An array of populated model objects.
 * @throws InvalidArgumentException If $modelClass is not a valid class.
 * @author James Chen
 */
function convert_records_to_models(iterable $records, string $modelClass): array
{
    if (!class_exists($modelClass)) {
        throw new InvalidArgumentException(
            "The model class '$modelClass' does not exist."
        );
    }

    $models = [];
    foreach ($records as $record) {
        if (!is_array($record)) {
            throw new InvalidArgumentException(
                "Each record must be an associative array."
            );
        }

        $models[] = apply(new $modelClass(), function ($model) use ($record) {
            populate_model($model, $record);
        });
    }

    return $models;
}
