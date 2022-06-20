<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Modifier;

use ObjectQuery\ObjectQuery;

abstract class AbstractModifier implements ModifierInterface
{
    protected readonly ObjectQuery $parentQuery;

    public function __construct(ObjectQuery $parentQuery)
    {
        $this->parentQuery = $parentQuery;
    }
}
