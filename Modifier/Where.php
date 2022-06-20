<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Modifier;

use ObjectQuery\ObjectQuery;
use ObjectQuery\ObjectQueryContext;
use ObjectQuery\ObjectQueryContextEnvironment;

final class Where extends AbstractModifier
{
    private readonly \Closure $callback;

    public function __construct(ObjectQuery $parentQuery, callable $callback)
    {
        parent::__construct($parentQuery);

        $this->callback = $callback(...)->bindTo(null);
    }

    public function apply(array $source, ObjectQueryContext $context): array
    {
        $final = [];
        foreach ($source as $item) {
            $localContext = $context
                ->withEnvironment($item, [$this->parentQuery->getSourceAlias() => $item])
                ->getEnvironment($item);

            if (true === \call_user_func($this->callback, $item, new ObjectQueryContextEnvironment($localContext))) {
                $final[] = $item;
            }
        }

        return $final;
    }
}
