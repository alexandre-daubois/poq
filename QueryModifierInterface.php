<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery;

/**
 * Defines a Query modifier. A modifier is applied before any operation. This could be a `where` clause, as well
 * as an ordering, a shuffling, limiting the max number of results, etc.
 */
interface QueryModifierInterface
{
    /**
     * Applies the modifier to the given source, and returns the result of this modifier.
     *
     * @param QueryInterface $query
     *      The source to apply the modifier on.
     *
     *      Optional context if needed by the modifier.
     *
     * @return iterable
     *      The modified source.
     */
    public function apply(QueryInterface $query): iterable;
}
