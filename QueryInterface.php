<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery;

/**
 * Defines an object which will execute modifiers and operations to query data.
 * A QueryInterface could receive any iterable depending on the implementation. This could go from the
 * basic array of data to generators.
 */
interface QueryInterface
{
    /**
     * Creates a new object to query data.
     *
     * @param iterable $source
     *      The source to apply manipulations on.
     *
     * @param QueryContextInterface|null $context
     *      The optional context that forward needed information to the Query for its execution.
     *
     * @return QueryInterface
     */
    public static function from(iterable $source, QueryContextInterface $context = null): QueryInterface;

    /**
     * Get the context of the current Query, possibly modified by the latter.
     *
     * @return QueryContextInterface
     */
    public function getContext(): QueryContextInterface;

    /**
     * Gets the source of the Query.
     *
     * @return iterable
     */
    public function getSource(): iterable;

    /**
     * Applies a modifier to the Query.
     *
     * @param QueryModifierInterface $modifier
     *      The actual modifier to apply.
     *
     * @return QueryInterface
     */
    public function applyModifier(QueryModifierInterface $modifier): QueryInterface;

    /**
     * Applies an operation to the Query.
     *
     * @param QueryOperationInterface $operation
     *      The actual operation to apply.
     *
     * @return mixed
     */
    public function applyOperation(QueryOperationInterface $operation): mixed;
}
