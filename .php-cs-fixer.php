<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(['src', 'tests']);

return (new PhpCsFixer\Config())
    ->setCacheFile('var/.php-cs-fixer.cache')
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PhpCsFixer' => true,
        '@Symfony' => true,
        '@PER-CS2.0' => true,
        'binary_operator_spaces' => false,
        'cast_spaces' => true,
        'concat_space' => ['spacing' => 'one'],
        'declare_strict_types' => true,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => null,
            'import_functions' => null,
        ],
        'multiline_whitespace_before_semicolons' => false,
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_order' => true,
        'phpdoc_to_comment' => false,
        'php_unit_test_class_requires_covers' => false,
        'single_line_throw' => false,
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays', 'arguments', 'parameters'],
        ],
        'void_return' => true,
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
    ])
    ->setFinder($finder);
