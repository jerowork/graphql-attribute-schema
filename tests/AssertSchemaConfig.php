<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test;

use GraphQL\Type\Definition\Argument;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\EnumValueDefinition;
use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\InputObjectField;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\UnionType;
use PHPUnit\Framework\TestCase;

final readonly class AssertSchemaConfig
{
    /**
     * @param array<string, mixed> $expected
     */
    public static function assertObjectType(array $expected, ?object $type, ?bool $sortFields = false): void
    {
        TestCase::assertInstanceOf(ObjectType::class, $type);

        $fields = array_map(
            fn(FieldDefinition $field) => [
                'name' => $field->name,
                'type' => $field->getType()->toString(),
                'description' => $field->description,
                'deprecationReason' => $field->deprecationReason,
                'args' => array_map(
                    fn(Argument $arg) => [
                        'name' => $arg->name,
                        'type' => $arg->getType()->toString(),
                        'description' => $arg->description,
                        'deprecationReason' => $arg->deprecationReason,
                    ],
                    $field->args,
                ),
            ],
            $type->getFields(),
        );

        // Sort fields as with Queries and Mutations the order can differ on different environments (file loading)
        if ($sortFields) {
            ksort($fields);
        }

        TestCase::assertSame($expected, [
            'name' => $type->name,
            'description' => $type->description,
            'fields' => array_values($fields),
        ]);
    }

    /**
     * @param array<string, mixed> $expected
     */
    public static function assertInputObjectType(array $expected, ?object $type): void
    {
        TestCase::assertInstanceOf(InputObjectType::class, $type);
        TestCase::assertSame($expected, [
            'name' => $type->name,
            'description' => $type->description,
            'fields' => array_values(array_map(
                fn(InputObjectField $field) => [
                    'name' => $field->name,
                    'type' => $field->getType()->toString(),
                    'description' => $field->description,
                    'deprecationReason' => $field->deprecationReason,
                ],
                $type->getFields(),
            )),
        ]);
    }

    /**
     * @param array<string, mixed> $expected
     */
    public static function assertInterfaceType(array $expected, ?object $type): void
    {
        TestCase::assertInstanceOf(InterfaceType::class, $type);
        TestCase::assertSame($expected, [
            'name' => $type->name,
            'description' => $type->description,
            'fields' => array_values(array_map(
                fn(FieldDefinition $field) => [
                    'name' => $field->name,
                    'type' => $field->getType()->toString(),
                    'description' => $field->description,
                    'deprecationReason' => $field->deprecationReason,
                    'args' => array_map(
                        fn(Argument $arg) => [
                            'name' => $arg->name,
                            'type' => $arg->getType()->toString(),
                            'description' => $arg->description,
                            'deprecationReason' => $arg->deprecationReason,
                        ],
                        $field->args,
                    ),
                ],
                $type->getFields(),
            )),
        ]);
    }

    /**
     * @param array<string, mixed> $expected
     */
    public static function assertUnionType(array $expected, ?object $type): void
    {
        TestCase::assertInstanceOf(UnionType::class, $type);
        TestCase::assertSame($expected, [
            'name' => $type->name,
            'description' => $type->description,
        ]);
    }

    /**
     * @param array<string, mixed> $expected
     */
    public static function assertEnumType(array $expected, ?object $type): void
    {
        TestCase::assertInstanceOf(EnumType::class, $type);
        TestCase::assertSame($expected, [
            'name' => $type->name,
            'description' => $type->description,
            'values' => array_values(array_map(
                fn(EnumValueDefinition $value) => [
                    'name' => $value->name,
                    'description' => $value->description,
                    'deprecationReason' => $value->deprecationReason,
                ],
                $type->getValues(),
            )),
        ]);
    }
}
