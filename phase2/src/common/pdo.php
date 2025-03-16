<?php

/**
 * Establishes a connection to the database using configuration parameters
 * from the config file.
 *
 * Sets up a PDO connection with proper error handling, fetch mode, and
 * prepared statement settings.
 *
 * @return PDO Stands for "PHP Data Objects". It is a database access layer for
 *             PHP that provides a consistent interface for connecting to and
 *             working with different types of databases.
 * @see config.php
 * @example
 *
 *     $pdo = $pdo_connect();
 *     $stmt = $pdo->prepare("SELECT * FROM account");
 *     $stmt->execute();
 *     $rows = $stmt->fetchAll();
 *
 * @author James Chen
 */
function pdo_connect(): PDO
{
    $config = require __DIR__ . '/config.php';
    $database_config = $config['database'];
    $host = $database_config['host'];
    $dbname = $database_config['dbname'];
    $username = $database_config['username'];
    $password = $database_config['password'];

    return new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            // This makes PDO throw exceptions when errors occur
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

            // This sets the default format for returning database results as
            // associative array. This means you can access results like
            // `$row['column_name']` instead of numeric indices
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

            // This disables emulated prepared statements in favor of native
            // ones
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
}

/**
 * Returns a singleton PDO instance to avoid multiple connections.
 *
 * This function ensures that only one instance of the PDO object is created
 * and reused throughout the application, preventing unnecessary database
 * connections.
 *
 * @return PDO|null The PDO database connection instance.
 * @author James Chen
 */
function pdo_instance(bool $original = false): PDO|null
{
    static $pdo;

    if ($original) {
        return $pdo;
    }

    if (!$pdo) {
        $pdo = pdo_connect();
    }

    return $pdo;
}

/**
 * Binds parameters to the given SQL statement.
 *
 * This function iterates through an array of parameters and binds each
 * parameter to the prepared PDO statement using bindValue(). The keys in the
 * parameters array are used as the parameter names in the SQL statement with a
 * colon prefix.
 *
 * @param PDOStatement $stmt The prepared PDO statement to bind parameters to.
 * @param array $params An associative array of parameters where keys correspond
 * to the named parameters in the SQL statement.
 * @return PDOStatement The PDO statement with bound parameters.
 * @example
 *
 *     $stmt = pdo_prepare("
 *         INSERT INTO account (email, password, type)
 *         VALUES (:email, :password, :type)
 *     ");
 *     bind_params($stmt, [
 *         "email" => $email,
 *         "password" => $password,
 *         "type" => $type
 *     ]);
 *     $stmt->execute();
 *
 * @author James Chen
 */
function bind_params(PDOStatement $stmt, array $params): PDOStatement
{
    foreach ($params as $key => $value) {
        $stmt->bindValue(':' . $key, $value);
    }

    return $stmt;
}

/**
 * Executes an SQL statement.
 *
 * This function executes a prepared PDO statement, optionally binding
 * parameters using the bind_params() function if parameters are provided. If
 * the $params array is not empty, the parameters will be bound to the statement
 * before execution.
 *
 * @param PDOStatement $stmt The prepared PDO statement to execute.
 * @param array $params Optional associative array of parameters to bind to the
 * statement.
 * @return bool True on success, false on failure.
 * @example
 *
 *     $stmt = pdo_prepare("
 *         INSERT INTO account (email, password, type)
 *         VALUES (:email, :password, :type)
 *     ");
 *     execute($stmt, [
 *         "email" => $email,
 *         "password" => $password,
 *         "type" => $type
 *     ]);
 *
 * @author James Chen
 */
function execute(PDOStatement $stmt, array $params = []): bool
{
    if ($params !== []) {
        bind_params($stmt, $params);
    }

    return $stmt->execute();
}
