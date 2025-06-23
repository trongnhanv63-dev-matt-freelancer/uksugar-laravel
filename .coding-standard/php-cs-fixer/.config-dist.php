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

// The `setRules` method defines the coding style rules to be applied.
return $config->setRules([
        // Use the PSR-12 standard as a base ruleset.
        '@PSR12' => true,

        // Enforce short array syntax: [] instead of array().
        'array_syntax' => ['syntax' => 'short'],

        // Imports should be ordered alphabetically.
        'ordered_imports' => ['sort_algorithm' => 'alpha'],

        // Automatically remove unused `use` statements.
        'no_unused_imports' => true,

        // Enforce a single blank line at the end of files.
        'single_blank_line_at_eof' => true,

        // Add a trailing comma in multiline arrays for cleaner git diffs.
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],

        // Standardize spacing around binary operators (e.g., $a = $b;).
        'binary_operator_spaces' => ['default' => 'single_space'],

        // Enforce the use of single quotes for simple strings.
        'single_quote' => true,
    ])
    ->setFinder($finder)
    ->setUsingCache(true)
    // Point cache file to the correct directory
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');
