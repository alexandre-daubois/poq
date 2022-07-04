<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Operation;

use ObjectQuery\QueryInterface;

final class Count extends AbstractQueryOperation
{
    public function apply(QueryInterface $query): int
    {
        return \count((array) $this->applySelect($query));
    }
}
