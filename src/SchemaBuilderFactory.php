<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema;

use Jerowork\GraphqlAttributeSchema\Resolver\BuiltTypesRegistry;
use Jerowork\GraphqlAttributeSchema\Resolver\RootTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\BuiltInScalarTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\BuiltTypesRegistryTypeResolverDecorator;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\Connection\EdgeTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\Connection\PageInfoTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\ConnectionTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\CustomScalarTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\EnumTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\FieldResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\InputObjectTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\InterfaceTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\ListAndNullableTypeResolverDecorator;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\ObjectTypeResolver;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\TypeResolverSelector;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\UnionTypeResolver;
use Psr\Container\ContainerInterface;

final readonly class SchemaBuilderFactory
{
    public function create(
        ContainerInterface $container,
    ): SchemaBuilder {
        $astContainer = new AstContainer();
        $builtTypesRegistry = new BuiltTypesRegistry();
        $fieldResolver = new FieldResolver($container);

        return new SchemaBuilder(
            $astContainer,
            new RootTypeResolver(
                new TypeResolverSelector([
                    new ListAndNullableTypeResolverDecorator(
                        new BuiltInScalarTypeResolver(),
                    ),
                    new ListAndNullableTypeResolverDecorator(new BuiltTypesRegistryTypeResolverDecorator(
                        $astContainer,
                        new CustomScalarTypeResolver($astContainer),
                        $builtTypesRegistry,
                    )),
                    new ListAndNullableTypeResolverDecorator(new BuiltTypesRegistryTypeResolverDecorator(
                        $astContainer,
                        new EnumTypeResolver($astContainer),
                        $builtTypesRegistry,
                    )),
                    new ListAndNullableTypeResolverDecorator(new BuiltTypesRegistryTypeResolverDecorator(
                        $astContainer,
                        new InputObjectTypeResolver($astContainer, $fieldResolver),
                        $builtTypesRegistry,
                    )),
                    new ListAndNullableTypeResolverDecorator(new BuiltTypesRegistryTypeResolverDecorator(
                        $astContainer,
                        new ObjectTypeResolver($astContainer, $fieldResolver),
                        $builtTypesRegistry,
                    )),
                    new ListAndNullableTypeResolverDecorator(new BuiltTypesRegistryTypeResolverDecorator(
                        $astContainer,
                        new InterfaceTypeResolver($astContainer, $builtTypesRegistry, $fieldResolver),
                        $builtTypesRegistry,
                    )),
                    new ListAndNullableTypeResolverDecorator(new BuiltTypesRegistryTypeResolverDecorator(
                        $astContainer,
                        new UnionTypeResolver($builtTypesRegistry),
                        $builtTypesRegistry,
                    )),
                    new ListAndNullableTypeResolverDecorator(
                        new ConnectionTypeResolver(
                            $astContainer,
                            $builtTypesRegistry,
                            new PageInfoTypeResolver($builtTypesRegistry),
                            new EdgeTypeResolver($astContainer, $builtTypesRegistry, $fieldResolver),
                        ),
                    ),
                ]),
                $container,
                $fieldResolver,
            ),
        );
    }
}
