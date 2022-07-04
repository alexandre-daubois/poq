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

final class Limit extends AbstractQueryModifier
{
    private readonly ?int $limit;

    public function __construct(ObjectQuery $parentQuery, ?int $limit)
    {
        parent::__construct($parentQuery);

        $this->limit = $limit;
    }

    public function apply(QueryInterface $query): iterable
    {
        if (null === $this->limit) {
            return $query->getSource();
        }

        if ($this->limit <= 0) {
            throw new InvalidModifierConfigurationException('limit', 'The limit must be a positive integer or null to set no limit');
        }

        return \array_slice((array) $query->getSource(), 0, $this->limit);
    }
}
