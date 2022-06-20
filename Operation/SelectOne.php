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
use ObjectQuery\ObjectQueryContext;

final class SelectOne extends AbstractOperation
{
    public function __construct(ObjectQuery $parentQuery, ?string $fields = null)
    {
        parent::__construct($parentQuery, $fields);

        $this->parentQuery = $parentQuery;
    }

    public function apply(array $source, ObjectQueryContext $context): mixed
    {
        $result = $this->applySelect($source, $context);

        $resultCount = \count($result);
        if ($resultCount > 1) {
            throw new NonUniqueResultException($resultCount);
        }

        return $result[0] ?? null;
    }
}
