<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('Entity')
    ->exclude('var')
    ->exclude('angular-front');

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'declare_strict_types' => true,
        'array_push' => true,
        'ordered_imports' => false,
        'concat_space' => ['spacing' => 'one'],
        'array_indentation' => true,
        'yoda_style' => false,
    ])
    ->setLineEnding("\r\n")
    ->setFinder($finder);
