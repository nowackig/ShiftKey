<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassConst\VarConstantCommentRector;
use Rector\Config\RectorConfig;
use Rector\Core\Configuration\Option;
use Rector\Laravel\Set\LaravelSetList;
use Rector\Privatization\Rector\Class_\RepeatedLiteralToClassConstantRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ArrayShapeFromConstantArrayReturnRector;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__.'/app/Http/Controllers/*.php',
        __DIR__.'/app/Http/Middleware',
        __DIR__.'/app/Http/Requests',
        __DIR__.'/app/Http/Resources',
        __DIR__.'/app/Http/*.php',
        __DIR__.'/app/ORM',
        __DIR__.'/app/Providers',
        __DIR__.'/app/Rules',
        __DIR__.'/app/database',
    ]);

    $rectorConfig->sets([
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::PHP_81,
        SetList::PRIVATIZATION,
        SetList::TYPE_DECLARATION,
        SetList::EARLY_RETURN,
    ]);

    $rectorConfig->skip([
        RepeatedLiteralToClassConstantRector::class,
        AddArrayReturnDocTypeRector::class,
        ArrayShapeFromConstantArrayReturnRector::class,
        VarConstantCommentRector::class
    ]);
};
