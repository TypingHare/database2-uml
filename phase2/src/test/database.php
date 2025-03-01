<?php

namespace test;

require_once __DIR__ . '/../common/functional.php';
require_once __DIR__ . '/../common/config.php';
require_once __DIR__ . '/../common/pdo.php';

use function common\get_pdo;

var_dump(get_pdo()->query('SELECT * FROM account')->fetchAll());
