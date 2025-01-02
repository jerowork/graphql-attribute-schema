<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Util\Finder\Native;

use Generator;
use Jerowork\GraphqlAttributeSchema\Util\Finder\Finder;
use Override;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

final readonly class NativeFinder implements Finder
{
    private const string REGEX_PHP_FILE = '/^.+\.php$/i';

    #[Override]
    public function findFiles(string ...$directories): Generator
    {
        foreach ($directories as $directory) {
            $filesIterator = new RegexIterator(
                new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)),
                self::REGEX_PHP_FILE,
                RegexIterator::GET_MATCH,
            );

            /** @var array<int, string> $filePath */
            foreach ($filesIterator as $filePath) {
                if (isset($filePath[0]) === false) {
                    continue;
                }

                yield $filePath[0];
            }
        }
    }
}
