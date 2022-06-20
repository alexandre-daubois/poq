<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Modifier;

use ObjectQuery\Exception\InvalidModifierConfigurationException;
use ObjectQuery\ObjectQuery;
use ObjectQuery\ObjectQueryContext;

final class Offset extends AbstractModifier
{
    private readonly ?int $offset;

    public function __construct(ObjectQuery $parentQuery, ?int $offset)
    {
        parent::__construct($parentQuery);

        $this->offset = $offset;
    }

    public function apply(array $source, ObjectQueryContext $context): array
    {
        if (null === $this->offset) {
            return $source;
        }

        if ($this->offset <= 0) {
            throw new InvalidModifierConfigurationException('offset', 'The offset must be a positive integer or null to set no offset.');
        }

        return \array_slice($source, $this->offset);
    }
}
