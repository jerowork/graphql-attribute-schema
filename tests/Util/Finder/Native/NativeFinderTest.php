<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Util\Finder\Native;

use Jerowork\GraphqlAttributeSchema\Util\Finder\Native\NativeFinder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Override;

/**
 * @internal
 */
final class NativeFinderTest extends TestCase
{
    private NativeFinder $finder;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->finder = new NativeFinder();
    }

    #[Test]
    public function itShouldRetrieveFilesFromFolder(): void
    {
        $files = iterator_to_array($this->finder->findFiles(__DIR__ . '/../../../Doubles/FullFeatured'));

        sort($files);

        $files = array_map(
            fn($file) => str_replace(__DIR__ . '/../../../', '', $file),
            $files,
        );

        self::assertSame([
            'Doubles/FullFeatured/Mutation/FoobarMutation.php',
            'Doubles/FullFeatured/Type/FoobarStatusType.php',
            'Doubles/FullFeatured/Type/FoobarType.php',
            'Doubles/FullFeatured/Type/Input/Baz.php',
            'Doubles/FullFeatured/Type/Input/MutateFoobarInputType.php',
        ], $files);
    }
}
