<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\Reference;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ConnectionTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\TypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\CustomScalarObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\EnumObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\InputTypeObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\ObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\TypeObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ExecutingObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ScalarTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\RootTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\ExecutingTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input\CustomScalarNodeInputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input\EdgeArgsInputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input\EnumNodeInputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input\InputTypeNodeInputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input\ScalarTypeInputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output\EnumNodeOutputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Output\ScalarTypeOutputFieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\FieldResolver;
use Jerowork\GraphqlAttributeSchema\TypeResolver\RootTypeResolver;
use Psr\Container\ContainerInterface;

final readonly class SchemaBuilderFactory
{
    public static function create(
        ContainerInterface $container,
    ): SchemaBuilder {
        $fieldResolver = new FieldResolver(
            $container,
            [
                new ScalarTypeOutputFieldResolver(),
                new EnumNodeOutputFieldResolver(),
            ],
        );

        /** @var iterable<ObjectTypeBuilder<Node>> $objectTypeBuilders */
        $objectTypeBuilders = [
            new EnumObjectTypeBuilder(),
            new InputTypeObjectTypeBuilder(),
            new TypeObjectTypeBuilder($fieldResolver),
            new CustomScalarObjectTypeBuilder(),
        ];

        $builtTypesRegistry = new BuiltTypesRegistry();

        /** @var iterable<TypeBuilder<Reference>> $typeBuilders */
        $typeBuilders = [
            new ScalarTypeBuilder(),
            new ConnectionTypeBuilder($builtTypesRegistry, $fieldResolver),
            new ExecutingObjectTypeBuilder($builtTypesRegistry, $objectTypeBuilders),
        ];

        return new SchemaBuilder(
            new RootTypeBuilder(
                new ExecutingTypeBuilder($typeBuilders),
                new RootTypeResolver(
                    $container,
                    [
                        new ScalarTypeInputFieldResolver(),
                        new EdgeArgsInputFieldResolver(),
                        new CustomScalarNodeInputFieldResolver(),
                        new EnumNodeInputFieldResolver(),
                        new InputTypeNodeInputFieldResolver(),
                    ],
                ),
            ),
        );
    }
}
