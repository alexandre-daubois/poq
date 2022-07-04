<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Operation;

use ObjectQuery\ObjectQuery;
use ObjectQuery\QueryInterface;

final class Each extends AbstractQueryOperation
{
    private \Closure $callback;

    public function __construct(ObjectQuery $parentQuery, callable $callback)
    {
        parent::__construct($parentQuery);

        $this->callback = $callback(...);
    }

    public function apply(QueryInterface $query): array
    {
        return \array_map($this->callback, (array) $this->applySelect($query));
    }
}
