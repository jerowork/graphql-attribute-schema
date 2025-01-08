<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Object\CustomScalarObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Object\EnumObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Object\InputTypeObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Object\ObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Object\TypeObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\RootTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Child\Input\CustomScalarNodeInputChildResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Child\Input\EnumNodeInputChildResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Child\Input\InputTypeNodeInputChildResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Child\Input\ScalarTypeInputChildResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Child\Output\EnumNodeOutputChildResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Child\Output\ScalarTypeOutputChildResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\FieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;
use Psr\Container\ContainerInterface;

final readonly class SchemaBuilderFactory
{
    public static function create(
        ContainerInterface $container,
    ): SchemaBuilder {
        /** @var iterable<ObjectTypeBuilder<Node>> $objectTypeBuilders */
        $objectTypeBuilders = [
            new EnumObjectTypeBuilder(),
            new InputTypeObjectTypeBuilder(),
            new TypeObjectTypeBuilder(
                new FieldResolver([
                    new ScalarTypeOutputChildResolver(),
                    new EnumNodeOutputChildResolver(),
                ]),
            ),
            new CustomScalarObjectTypeBuilder(),
        ];

        return new SchemaBuilder(
            new RootTypeBuilder(
                new TypeBuilder($objectTypeBuilders),
                new RootTypeResolver(
                    $container,
                    [
                        new ScalarTypeInputChildResolver(),
                        new CustomScalarNodeInputChildResolver(),
                        new EnumNodeInputChildResolver(),
                        new InputTypeNodeInputChildResolver(),
                    ],
                ),
            ),
        );
    }
}
