<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test;

use ArrayObject;
use Jerowork\GraphqlAttributeSchema\Context;
use Jerowork\GraphqlAttributeSchema\ContextException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @internal
 */
final class ContextTest extends TestCase
{
    #[Test]
    public function itShouldAttachAndGetObjectByClass(): void
    {
        $context = new Context();
        $context->attachObject($object = new stdClass());

        self::assertSame($object, $context->getObject(stdClass::class));
        self::assertSame($object, $context->maybeGetObject(stdClass::class));
    }

    #[Test]
    public function itShouldReturnNullWhenMaybeGetObjectIsMissing(): void
    {
        self::assertNull((new Context())->maybeGetObject(stdClass::class));
    }

    #[Test]
    public function itShouldThrowWhenGetObjectIsMissing(): void
    {
        self::expectException(ContextException::class);

        (new Context())->getObject(stdClass::class);
    }

    #[Test]
    public function itShouldThrowWhenStoredObjectIsOfUnexpectedType(): void
    {
        $context = new Context([stdClass::class => new ArrayObject()]);

        self::expectException(ContextException::class);

        $context->getObject(stdClass::class);
    }

    #[Test]
    public function itShouldBehaveAsArrayAccess(): void
    {
        $context = new Context();
        $context['locale'] = 'en';

        self::assertTrue(isset($context['locale']));
        self::assertSame('en', $context['locale']);
        self::assertNull($context['missing']);

        unset($context['locale']);
        self::assertFalse(isset($context['locale']));
    }

    #[Test]
    public function itShouldIterateOverStoredValues(): void
    {
        $context = new Context(['a' => 1, 'b' => 2]);

        self::assertSame(['a' => 1, 'b' => 2], iterator_to_array($context));
    }

    #[Test]
    public function itShouldCloneIntoAnIndependentCopy(): void
    {
        $context = new Context();
        $context->attachObject($object = new stdClass());

        $clone = $context->clone();
        $clone['extra'] = 'value';

        // The clone keeps the parent's data...
        self::assertSame($object, $clone->getObject(stdClass::class));
        // ...but mutations on the clone do not leak back to the parent.
        self::assertFalse(isset($context['extra']));
    }
}
