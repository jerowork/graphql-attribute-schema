<?php

declare(strict_types=1);

namespace Jerowork\GraphqlAttributeSchema\Attribute;

use Attribute;

/**
 * Maps a resolver method parameter to an object stored in the GraphQL Context.
 *
 * The object is fetched from the Context by the parameter's type. A nullable parameter resolves to
 * null when the object is absent; a non-nullable parameter throws when it is absent.
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
final readonly class MapContext {}
