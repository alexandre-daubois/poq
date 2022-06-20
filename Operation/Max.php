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

final class Max extends AbstractOperation
{
    public function __construct(ObjectQuery $parentQuery, string $field)
    {
        parent::__construct($parentQuery, $field);
    }

    public function apply(array $source, ObjectQueryContext $context): mixed
    {
        $source = $this->applySelect($source, $context);

        return empty($source) ? null : \max($source);
    }
}
