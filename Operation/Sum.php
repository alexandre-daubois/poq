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
use ObjectQuery\ObjectQueryContext;

final class Sum extends AbstractOperation
{
    public function __construct(ObjectQuery $parentQuery, string $field)
    {
        parent::__construct($parentQuery, $field);
    }

    public function apply(array $source, ObjectQueryContext $context): int|float
    {
        $source = $this->applySelect($source, $context);

        if (\count($source) !== \count(\array_filter($source, 'is_numeric'))) {
            throw new IncompatibleCollectionException('sum', 'Operation can only be applied to a collection of numerics');
        }

        return \array_sum($source);
    }
}
