<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test;

use Jerowork\GraphqlAttributeSchema\Ast;
use Jerowork\GraphqlAttributeSchema\AstContainer;
use LogicException;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AstContainerTest extends TestCase
{
    private AstContainer $astContainer;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->astContainer = new AstContainer();
    }

    #[Test]
    public function itShouldThrowExceptionWhenAstNotFoundInContainer(): void
    {
        self::expectException(LogicException::class);

        $this->astContainer->getAst();
    }

    #[Test]
    public function itShouldSetAstInContainer(): void
    {
        $ast = new Ast();
        $this->astContainer->setAst($ast);

        self::assertSame($ast, $this->astContainer->getAst());
    }
}
