<?php

echo json_encode([
    'status' => 'error',
    'message' => 'Page or Api not found: ' . $_SERVER['REQUEST_URI']
]);
