<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Operation;

use ObjectQuery\Exception\IncompatibleFieldException;
use ObjectQuery\ObjectQuery;
use ObjectQuery\ObjectQueryContext;
use Symfony\Component\PropertyAccess\PropertyAccess;

final class SelectMany extends AbstractOperation
{
    private readonly string $field;
    private readonly string $alias;

    public function __construct(ObjectQuery $parentQuery, string $field, string $alias)
    {
        parent::__construct($parentQuery);

        $this->field = $field;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
        $this->alias = $alias;
    }

    public function apply(array $source, ObjectQueryContext $context): ObjectQuery
    {
        $source = $this->applySelect($source, $context);

        $final = [];
        $context = $this->parentQuery->getContext();
        foreach ($source as $item) {
            $subfields = $this->propertyAccessor->getValue($item, $this->field);

            if (!\is_array($subfields) || \count(\array_filter($subfields, 'is_object')) !== \count($subfields)) {
                throw new IncompatibleFieldException('selectMany', 'You can only selectMany on fields that are collections of objects');
            }

            foreach ($subfields as $subfield) {
                $final[] = $subfield;

                $context = $context->withEnvironment($subfield, [$this->parentQuery->getSourceAlias() => $item]);

                // Transmit current context to descendants
                $context = $context->withEnvironment($subfield, $context->getEnvironment($item));
            }
        }

        return (new ObjectQuery($context))
            ->from($final, $this->alias);
    }
}
