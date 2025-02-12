<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Resolver\Type;

use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\Node\InputTypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeNode;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ObjectTypeReference;
use Jerowork\GraphqlAttributeSchema\Node\TypeReference\ScalarTypeReference;
use Jerowork\GraphqlAttributeSchema\Resolver\Type\GetNodeFromReferenceTrait;
use LogicException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @internal
 */
final class GetNodeFromReferenceTraitTest extends TestCase
{
    #[Test]
    public function itShouldThrowExceptionWhenReferenceIsInvalidType(): void
    {
        $trait = new class {
            use GetNodeFromReferenceTrait;
        };

        self::expectException(LogicException::class);
        self::expectExceptionMessage('Reference must implement ObjectTypeReference or ConnectionTypeReference');

        $trait->getNodeFromReference(ScalarTypeReference::create('string'), new Ast(), TypeNode::class);
    }

    #[Test]
    public function itShouldThrowExceptionIfNodeIsNotFound(): void
    {
        $trait = new class {
            use GetNodeFromReferenceTrait;
        };

        self::expectException(LogicException::class);
        self::expectExceptionMessage('No node found for reference');

        $trait->getNodeFromReference(ObjectTypeReference::create(stdClass::class), new Ast(), TypeNode::class);
    }

    #[Test]
    public function itShouldThrowExceptionIfNodeIsNotOfGivenClass(): void
    {
        $trait = new class {
            use GetNodeFromReferenceTrait;
        };

        $node = new TypeNode(
            stdClass::class,
            'name',
            null,
            [],
            null,
            [],
        );

        self::expectException(LogicException::class);
        self::expectExceptionMessage('must implement ' . InputTypeNode::class);

        $trait->getNodeFromReference(ObjectTypeReference::create(stdClass::class), new Ast($node), InputTypeNode::class);
    }

    #[Test]
    public function itShouldGetNode(): void
    {
        $trait = new class {
            use GetNodeFromReferenceTrait;
        };

        $node = new TypeNode(
            stdClass::class,
            'name',
            null,
            [],
            null,
            [],
        );

        self::assertSame(
            $node,
            $trait->getNodeFromReference(ObjectTypeReference::create(stdClass::class), new Ast($node), TypeNode::class),
        );
    }
}
