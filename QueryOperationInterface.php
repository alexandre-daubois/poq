<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery;

/**
 * Defines a final operation done to the source, after modifiers has been applied. An operation can be
 * a simple concatenation, selecting data, get an average/min/max value, etc.
 */
interface QueryOperationInterface
{
    /**
     * @param QueryInterface $query
     *      The source to apply the operation on.
     *
     * @return mixed
     *      The result of the operation. This can be any type of data, depending on what the operation actually does.
     */
    public function apply(QueryInterface $query): mixed;
}
