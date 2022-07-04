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

final class Max extends AbstractQueryOperation
{
    public function __construct(ObjectQuery $parentQuery, string $field)
    {
        parent::__construct($parentQuery, $field);
    }

    public function apply(QueryInterface $query): mixed
    {
        $source = (array) $this->applySelect($query);

        return empty($source) ? null : \max($source);
    }
}
