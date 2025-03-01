<?php

function initialize(): void
{
    // Automatically loads PHP classes when they are referenced in the code but
    // haven't been manually included yet
    spl_autoload_register(function ($class) {
        $file = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';

        if (!file_exists($file)) {
            return false;
        }

        require_once $file;
        return true;
    });

    /** @noinspection PhpForeachOverSingleElementArrayLiteralInspection */
    foreach (["common"] as $item) {
        $functionFiles = glob(__DIR__ . '/../' . $item . '/*.php');
        foreach ($functionFiles as $functionFile) {
            require_once $functionFile;
        }
    }
}

initialize();
