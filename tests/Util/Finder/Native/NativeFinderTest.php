<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Test\Util\Finder\Native;

use Jerowork\GraphqlAttributeSchema\Util\Finder\Native\NativeFinder;
use Override;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

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
            'Doubles/FullFeatured/Mutation/BasicMutation.php',
            'Doubles/FullFeatured/Query/BasicQuery.php',
            'Doubles/FullFeatured/Query/DeprecatedQuery.php',
            'Doubles/FullFeatured/Query/WithConnectionOutputQuery.php',
            'Doubles/FullFeatured/Query/WithInputObjectQuery.php',
            'Doubles/FullFeatured/Query/WithInterfaceOutputQuery.php',
            'Doubles/FullFeatured/Query/WithListOutputQuery.php',
            'Doubles/FullFeatured/Query/WithOverwrittenTypeQuery.php',
            'Doubles/FullFeatured/Query/WithUnionOutputQuery.php',
            'Doubles/FullFeatured/Type/AbstractAdminType.php',
            'Doubles/FullFeatured/Type/AgentType.php',
            'Doubles/FullFeatured/Type/FoobarStatusType.php',
            'Doubles/FullFeatured/Type/FoobarType.php',
            'Doubles/FullFeatured/Type/Input/Baz.php',
            'Doubles/FullFeatured/Type/Input/MutateFoobarInputType.php',
            'Doubles/FullFeatured/Type/Input/QueryInputType.php',
            'Doubles/FullFeatured/Type/RecipientType.php',
            'Doubles/FullFeatured/Type/Scalar/TestScalarType.php',
            'Doubles/FullFeatured/Type/SomeInterface.php',
            'Doubles/FullFeatured/Type/UserType.php',
        ], $files);
    }
}
