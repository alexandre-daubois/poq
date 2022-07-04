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
use ObjectQuery\QueryInterface;

final class Offset extends AbstractQueryModifier
{
    private readonly ?int $offset;

    public function __construct(ObjectQuery $parentQuery, ?int $offset)
    {
        parent::__construct($parentQuery);

        $this->offset = $offset;
    }

    public function apply(QueryInterface $query): iterable
    {
        if (null === $this->offset) {
            return $query->getSource();
        }

        if ($this->offset <= 0) {
            throw new InvalidModifierConfigurationException('offset', 'The offset must be a positive integer or null to set no offset.');
        }

        return \array_slice((array) $query->getSource(), $this->offset);
    }
}
