<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Doubles\Query;

use Jerowork\GraphqlAttributeSchema\Attribute\Option\ListType;
use Jerowork\GraphqlAttributeSchema\Attribute\Option\UnionType;
use Jerowork\GraphqlAttributeSchema\Attribute\Query;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestType;
use Jerowork\GraphqlAttributeSchema\Test\Doubles\Type\TestTypeWithAutowire;

final readonly class TestQueryWithUnionType
{
    #[Query(type: new UnionType('FoobarResults'))]
    public function getFoobars(): TestType|TestTypeWithAutowire
    {
        return '';
    }

    #[Query]
    public function getBazs(): TestType|TestTypeWithAutowire
    {
        return '';
    }

    #[Query]
    public function getNullableBazs(): null|TestType|TestTypeWithAutowire
    {
        return '';
    }

    #[Query(type: new UnionType('FoobarResults', TestType::class, TestTypeWithAutowire::class))]
    public function getQuxs(): void {}

    #[Query(type: new ListType(new UnionType('FoobarResults', TestType::class, TestTypeWithAutowire::class)))]
    public function getListOfQuxs(): array
    {
        return [];
    }
}
