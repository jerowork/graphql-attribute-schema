<?php

declare(strict_types=1);

use Jerowork\GraphqlAttributeSchema\Test\Util\Reflector\Native\Doubles\AnotherClass;
use Jerowork\GraphqlAttributeSchema\Test\Util\Reflector\Native\Doubles\AnotherEnum;
use Jerowork\GraphqlAttributeSchema\Test\Util\Reflector\Native\Doubles\AnotherInterface;
use Jerowork\GraphqlAttributeSchema\Test\Util\Reflector\Native\Doubles\SomeEnum;
use Jerowork\GraphqlAttributeSchema\Test\Util\Reflector\Native\Doubles\SomeInterface;
use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;

$config = new Configuration();

$config->ignoreUnknownClasses([
    AnotherClass::class,
    AnotherEnum::class,
    AnotherInterface::class,
    SomeEnum::class,
    SomeInterface::class,
]);

return $config;
