<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Modifier;

use ObjectQuery\ObjectQuery;
use ObjectQuery\ObjectQueryContextEnvironment;
use ObjectQuery\QueryInterface;

final class Where extends AbstractQueryModifier
{
    private readonly \Closure $callback;

    public function __construct(ObjectQuery $parentQuery, callable $callback)
    {
        parent::__construct($parentQuery);

        $this->callback = $callback(...)->bindTo(null);
    }

    public function apply(QueryInterface $query): array
    {
        $final = [];
        foreach ($query->getSource() as $item) {
            $localContext = $query->getContext()
                ->withEnvironment($item, [$this->parentQuery->getSourceAlias() => $item])
                ->getEnvironment($item);

            if (true === \call_user_func($this->callback, $item, new ObjectQueryContextEnvironment($localContext))) {
                $final[] = $item;
            }
        }

        return $final;
    }
}
