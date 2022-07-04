<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Operation;

use ObjectQuery\Exception\IncompatibleCollectionException;
use ObjectQuery\ObjectQuery;
use ObjectQuery\QueryInterface;

final class Average extends AbstractQueryOperation
{
    public function __construct(ObjectQuery $parentQuery, string $field)
    {
        parent::__construct($parentQuery, $field);
    }

    public function apply(QueryInterface $query): float
    {
        $source = (array) $this->applySelect($query);

        $count = \count($source);
        if ($count !== \count(\array_filter($source, 'is_numeric'))) {
            throw new IncompatibleCollectionException('average', 'Operation can only be applied to a collection of numerics');
        }

        return (float) (\array_sum($source) / $count);
    }
}
