<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Modifier;

use ObjectQuery\ObjectQuery;
use ObjectQuery\QueryModifierInterface;

abstract class AbstractQueryModifier implements QueryModifierInterface
{
    protected readonly ObjectQuery $parentQuery;

    public function __construct(ObjectQuery $parentQuery)
    {
        $this->parentQuery = $parentQuery;
    }
}
