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

final class Select extends AbstractOperation
{
    public function __construct(ObjectQuery $parentQuery, array|string|null $fields = null)
    {
        parent::__construct($parentQuery, $fields);

        $this->parentQuery = $parentQuery;
    }

    public function apply(array $source, ObjectQueryContext $context): array
    {
        return $this->applySelect($source, $context);
    }
}
