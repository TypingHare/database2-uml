<?php /** @noinspection ALL */

/**
 * PHP CS Fixer configuration file.
 *
 * @author James Chen
 */

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/phase2/src',
    ]);

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR12' => true,
    'array_syntax' => ['syntax' => 'short'],
    'ordered_imports' => ['sort_algorithm' => 'alpha'],
    'no_unused_imports' => true,
    'line_length' => ['max' => 80],
])->setFinder($finder);
