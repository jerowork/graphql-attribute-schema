<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Resolver\Type\Connection;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Jerowork\GraphqlAttributeSchema\Resolver\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\Connection\PageInfoTypeResolver;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class PageInfoTypeResolverTest extends TestCase
{
    private PageInfoTypeResolver $resolver;
    private BuiltTypesRegistry $builtTypesRegistry;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new PageInfoTypeResolver(
            $this->builtTypesRegistry = new BuiltTypesRegistry(),
        );
    }

    #[Test]
    public function itShouldBuildPageInfoTypeAndStoreInRegistry(): void
    {
        self::assertFalse($this->builtTypesRegistry->hasType('PageInfo'));

        $pageInfo = $this->resolver->createPageInfo();

        self::assertTrue($this->builtTypesRegistry->hasType('PageInfo'));

        self::assertSame($this->builtTypesRegistry->getType('PageInfo'), $pageInfo);

        self::assertEquals(new ObjectType([
            'name' => 'PageInfo',
            'fields' => [
                [
                    'name' => 'hasPreviousPage',
                    'type' => Type::nonNull(Type::boolean()),
                ],
                [
                    'name' => 'hasNextPage',
                    'type' => Type::nonNull(Type::boolean()),
                ],
                [
                    'name' => 'startCursor',
                    'type' => Type::string(),
                ],
                [
                    'name' => 'endCursor',
                    'type' => Type::string(),
                ],
            ],
        ]), $pageInfo);
    }

    #[Test]
    public function itShouldGetPageInfoTypeFromRegistry(): void
    {
        $this->builtTypesRegistry->addType(
            'PageInfo',
            $expectedPageInfo = new ObjectType([
                'name' => 'PageInfo',
                'fields' => [
                    [
                        'name' => 'hasPreviousPage',
                        'type' => Type::nonNull(Type::boolean()),
                    ],
                    [
                        'name' => 'hasNextPage',
                        'type' => Type::nonNull(Type::boolean()),
                    ],
                    [
                        'name' => 'startCursor',
                        'type' => Type::string(),
                    ],
                    [
                        'name' => 'endCursor',
                        'type' => Type::string(),
                    ],
                ],
            ]),
        );

        self::assertTrue($this->builtTypesRegistry->hasType('PageInfo'));

        $pageInfo = $this->resolver->createPageInfo();

        self::assertSame($expectedPageInfo, $pageInfo);
    }
}
