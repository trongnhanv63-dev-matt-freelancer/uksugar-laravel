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

        // --- Các quy tắc bổ sung để code gọn gàng hơn ---

        // Loại bỏ các dòng trống thừa. Mỗi khối code chỉ cách nhau tối đa 1 dòng trống.
        'no_extra_blank_lines' => [
            'tokens' => [
                'extra',
                'throw',
                'use',
            ],
        ],

        // Không có dòng trống ngay sau khi mở class `class MyClass { ...`.
        'no_blank_lines_after_class_opening' => true,

        // Đảm bảo chỉ có một khoảng trắng xung quanh toán tử nối chuỗi (dấu chấm).
        'concat_space' => ['spacing' => 'one'],

        // Viết liền nullable typehint, ví dụ `?string` thay vì `? string`.
        'compact_nullable_typehint' => true,

        // Căn chỉnh thụt lề cho các chuỗi gọi phương thức (fluent interface).
        'method_chaining_indentation' => true,

        // Loại bỏ khoảng trắng ở cuối các dòng trống.
        'no_whitespace_in_blank_line' => true,

        // Thống nhất cách viết hoa/thường cho các hằng số (true, false, null).
        'constant_case' => ['case' => 'lower'],
    ])
    ->setFinder($finder)
    ->setUsingCache(true)
    // Point cache file to the correct directory
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');
