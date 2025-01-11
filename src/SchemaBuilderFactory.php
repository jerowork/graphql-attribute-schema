<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema;

use Jerowork\GraphqlAttributeSchema\Parser\Node\Node;
use Jerowork\GraphqlAttributeSchema\Parser\Node\Reference\Reference;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\NodeTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\CustomScalarObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\EnumObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\InputTypeObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\ObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\Object\TypeObjectTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ObjectNodeTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\Type\ScalarNodeTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\RootTypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeBuilder\TypeBuilder;
use Jerowork\GraphqlAttributeSchema\TypeResolver\Field\Input\CustomScalarNodeInputFieldResolver;
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
        /** @var iterable<ObjectTypeBuilder<Node>> $objectTypeBuilders */
        $objectTypeBuilders = [
            new EnumObjectTypeBuilder(),
            new InputTypeObjectTypeBuilder(),
            new TypeObjectTypeBuilder(
                new FieldResolver(
                    $container,
                    [
                        new ScalarTypeOutputFieldResolver(),
                        new EnumNodeOutputFieldResolver(),
                    ],
                ),
            ),
            new CustomScalarObjectTypeBuilder(),
        ];

        /** @var iterable<NodeTypeBuilder<Reference>> $nodeTypeBuilders */
        $nodeTypeBuilders = [
            new ScalarNodeTypeBuilder(),
            new ObjectNodeTypeBuilder(new BuiltTypesRegistry(), $objectTypeBuilders),
        ];

        return new SchemaBuilder(
            new RootTypeBuilder(
                new TypeBuilder($nodeTypeBuilders),
                new RootTypeResolver(
                    $container,
                    [
                        new ScalarTypeInputFieldResolver(),
                        new CustomScalarNodeInputFieldResolver(),
                        new EnumNodeInputFieldResolver(),
                        new InputTypeNodeInputFieldResolver(),
                    ],
                ),
            ),
        );
    }
}
