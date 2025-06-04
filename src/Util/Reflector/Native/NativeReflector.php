<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Util\Reflector\Native;

use Jerowork\GraphqlAttributeSchema\Util\Reflector\Reflector;
use PhpToken;
use ReflectionClass;

/**
 * @internal
 */
final readonly class NativeReflector implements Reflector
{
    public function getClasses(string $filePath): array
    {
        $content = file_get_contents($filePath);

        if (!$content) {
            return [];
        }

        $tokens = PhpToken::tokenize($content);

        $classes = [];
        $namespace = '';
        $collectNamespace = false;

        foreach ($tokens as $index => $token) {
            if ($token->is(T_NAMESPACE)) {
                // Start collecting namespace parts
                $namespace = '';
                $collectNamespace = true;

                continue;
            }

            if ($collectNamespace) {
                if ($token->is(T_NAME_QUALIFIED)) {
                    $namespace .= $token->text;
                } elseif ($token->is(T_WHITESPACE)) {
                    continue;
                } else {
                    // End of namespace declaration
                    $collectNamespace = false;
                }
            }

            if ($token->is([T_CLASS, T_INTERFACE, T_ENUM])) {
                // Skip anonymous classes
                $next = $tokens[$index + 1] ?? null;
                while ($next && $next->is(T_WHITESPACE)) {
                    ++$index;
                    $next = $tokens[$index + 1] ?? null;
                }

                if ($next && $next->is(T_STRING)) {
                    /** @var class-string $className */
                    $className = trim($namespace . '\\' . $next->text, '\\');
                    $classes[] = new ReflectionClass($className);
                }
            }
        }

        return $classes;
    }
}
