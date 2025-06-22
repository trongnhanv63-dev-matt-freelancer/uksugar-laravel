<?php

// Configuration file for PHP CS Fixer

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/../../app',
        __DIR__ . '/../../config',
        __DIR__ . '/../../database/factories',
        __DIR__ . '/../../database/seeders',
        __DIR__ . '/../../routes',
        __DIR__ . '/../../tests',
    ]);

$config = new PhpCsFixer\Config();

return $config->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        'single_blank_line_at_eof' => true,
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],
        'binary_operator_spaces' => ['default' => 'single_space'],

        // Corrected rule name for enforcing single quotes.
        'single_quote' => true,
    ])
    ->setFinder($finder)
    ->setUsingCache(true)
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');
