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

final class Select extends AbstractQueryOperation
{
    public function __construct(ObjectQuery $parentQuery, array|string|null $fields = null)
    {
        parent::__construct($parentQuery, $fields);

        $this->parentQuery = $parentQuery;
    }

    public function apply(QueryInterface $query): iterable
    {
        return $this->applySelect($query);
    }
}
