<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Operation;

use ObjectQuery\ObjectQuery;
use ObjectQuery\ObjectQueryContext;

final class Each extends AbstractOperation
{
    private \Closure $callback;

    public function __construct(ObjectQuery $parentQuery, callable $callback)
    {
        parent::__construct($parentQuery);

        $this->callback = $callback(...);
    }

    public function apply(array $source, ObjectQueryContext $context): array
    {
        return \array_map($this->callback, $this->applySelect($source, $context));
    }
}
