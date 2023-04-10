<?php

$finder = PhpCsFixer\Finder::create()
    ->files()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/tests',
        __DIR__ . '/config',
        __DIR__ . '/database',
    ]);
;

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PHP81Migration' => true,
    '@PSR12' => true,
    '@PhpCsFixer' => true,
    'array_syntax' => ['syntax' => 'short'],
    'declare_strict_types' => true,
    'php_unit_internal_class' => [],
    'php_unit_method_casing' => ['case' => 'snake_case'],
    'php_unit_test_class_requires_covers'=>false,
    'strict_param' => true,
])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ;
