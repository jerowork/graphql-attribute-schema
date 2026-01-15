<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\RemoveSoleValueSprintfRector;
use Rector\CodeQuality\Rector\FuncCall\UnwrapSprintfOneArgumentRector;
use Rector\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector;
use Rector\CodingStyle\Rector\FuncCall\CountArrayToEmptyArrayComparisonRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Array_\RemoveDuplicatedArrayKeyRector;
use Rector\DeadCode\Rector\Cast\RecastingRemovalRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector;
use Rector\DeadCode\Rector\If_\ReduceAlwaysFalseIfOrRector;
use Rector\DeadCode\Rector\If_\RemoveUnusedNonEmptyArrayBeforeForeachRector;
use Rector\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector;
use Rector\DeadCode\Rector\Property\RemoveUselessReadOnlyTagRector;
use Rector\DeadCode\Rector\Property\RemoveUselessVarTagRector;
use Rector\DeadCode\Rector\Ternary\TernaryToBooleanOrFalseToBooleanAndRector;
use Rector\Php80\Rector\Catch_\RemoveUnusedVariableInCatchRector;
use Rector\Php80\Rector\ClassConstFetch\ClassOnThisVariableObjectRector;
use Rector\Php80\Rector\FuncCall\ClassOnObjectRector;
use Rector\Php80\Rector\Identical\StrEndsWithRector;
use Rector\Php80\Rector\Identical\StrStartsWithRector;
use Rector\Php80\Rector\NotIdentical\StrContainsRector;
use Rector\Php81\Rector\Array_\ArrayToFirstClassCallableRector;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Php82\Rector\Class_\ReadOnlyClassRector;
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector;
use Rector\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector;

$config = RectorConfig::configure()
    ->withCache(__DIR__ . '/var/rector')
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withParallel()
    ->withRules([
        // Misc
        PrivatizeFinalClassPropertyRector::class,
        PrivatizeFinalClassMethodRector::class,
        UnwrapSprintfOneArgumentRector::class,
        RemoveSoleValueSprintfRector::class,
        CountArrayToEmptyArrayComparisonRector::class,
        // See withConfiguredRule below for more

        // Misc - Deadcode
        RemoveUnusedForeachKeyRector::class,
        RemoveDuplicatedArrayKeyRector::class,
        RecastingRemovalRector::class,
        RemoveUnusedNonEmptyArrayBeforeForeachRector::class,
        TernaryToBooleanOrFalseToBooleanAndRector::class,
        RemoveUselessParamTagRector::class,
        RemoveUselessReturnTagRector::class,
        RemoveUselessReadOnlyTagRector::class,
        RemoveNonExistingVarAnnotationRector::class,
        RemoveUselessVarTagRector::class,
        ReduceAlwaysFalseIfOrRector::class,

        // PHP 8.0
        ClassOnThisVariableObjectRector::class,
        ClassOnObjectRector::class,
        StrStartsWithRector::class,
        StrEndsWithRector::class,
        StrContainsRector::class,
        RemoveUnusedVariableInCatchRector::class,

        // PHP 8.1
        ReadOnlyPropertyRector::class,
        ArrayToFirstClassCallableRector::class,

        // PHP 8.2
        ReadOnlyClassRector::class,

        // PHP 8.3
        AddTypeToConstRector::class,
        AddOverrideAttributeToOverriddenMethodsRector::class,
    ])
    ->withConfiguredRule(SimplifyUselessVariableRector::class, [
        SimplifyUselessVariableRector::ONLY_DIRECT_ASSIGN => true,
    ]);

return $config;
