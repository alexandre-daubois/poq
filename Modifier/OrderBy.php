<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Modifier;

use ObjectQuery\Exception\InvalidModifierConfigurationException;
use ObjectQuery\ObjectQuery;
use ObjectQuery\ObjectQueryOrder;
use ObjectQuery\QueryInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

final class OrderBy extends AbstractQueryModifier
{
    private readonly ObjectQueryOrder $orderBy;
    private readonly ?string $orderField;

    protected PropertyAccessor $propertyAccessor;

    public function __construct(ObjectQuery $parentQuery, ObjectQueryOrder $orderBy = ObjectQueryOrder::None, ?string $orderField = null)
    {
        parent::__construct($parentQuery);

        $this->orderBy = $orderBy;
        $this->orderField = $orderField;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function apply(QueryInterface $query): iterable
    {
        if (null !== $this->orderField && ObjectQueryOrder::Shuffle === $this->orderBy) {
            throw new InvalidModifierConfigurationException('orderBy', 'An order field must not be provided when shuffling a collection');
        }

        $source = (array) $query->getSource();

        if (ObjectQueryOrder::Shuffle === $this->orderBy) {
            \shuffle($source);

            return $source;
        }

        if (ObjectQueryOrder::None !== $this->orderBy) {
            if (null === $this->orderField) {
                throw new InvalidModifierConfigurationException('orderBy', 'An order field must be provided');
            }

            \usort($source, function ($elementA, $elementB) {
                return $this->propertyAccessor->getValue($elementA, $this->orderField) <=> $this->propertyAccessor->getValue($elementB, $this->orderField);
            });

            if (ObjectQueryOrder::Descending === $this->orderBy) {
                $source = \array_reverse($source);
            }

            return $source;
        }

        return $source;
    }
}
