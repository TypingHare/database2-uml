<?php

namespace test;

require_once __DIR__ . '/../common/pdo.php';

use function common\connect_database;

$pdo = connect_database();
var_dump($pdo->query('SELECT * FROM account')->fetchAll());
