<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Operation;

use ObjectQuery\Exception\NonUniqueResultException;
use ObjectQuery\ObjectQuery;
use ObjectQuery\QueryInterface;

final class SelectOne extends AbstractQueryOperation
{
    public function __construct(ObjectQuery $parentQuery, ?string $fields = null)
    {
        parent::__construct($parentQuery, $fields);

        $this->parentQuery = $parentQuery;
    }

    public function apply(QueryInterface $query): mixed
    {
        $result = (array) $this->applySelect($query);

        $resultCount = \count($result);
        if ($resultCount > 1) {
            throw new NonUniqueResultException($resultCount);
        }

        return $result[0] ?? null;
    }
}
