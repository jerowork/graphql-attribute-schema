<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema;

use LogicException;

final class AstContainer
{
    private Ast $ast;

    public function setAst(Ast $ast): void
    {
        $this->ast = $ast;
    }

    public function getAst(): Ast
    {
        if (!isset($this->ast)) {
            throw new LogicException('Ast container has not been set');
        }

        return $this->ast;
    }
}
