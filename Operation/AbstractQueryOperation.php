<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Operation;

use ObjectQuery\ObjectQuery;
use ObjectQuery\QueryInterface;
use ObjectQuery\QueryOperationInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

abstract class AbstractQueryOperation implements QueryOperationInterface
{
    protected readonly array|string|null $fields;
    protected ObjectQuery $parentQuery;
    protected PropertyAccessor $propertyAccessor;

    public function __construct(ObjectQuery $parentQuery, array|string|null $fields = null)
    {
        $this->parentQuery = $parentQuery;
        $this->fields = $fields;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    protected function applySelect(QueryInterface $query): iterable
    {
        if ($where = $query->getWhere()) {
            $query->applyModifier($where);
        }

        if ($offset = $query->getOffset()) {
            $query->applyModifier($offset);
        }

        if ($limit = $query->getLimit()) {
            $query->applyModifier($limit);
        }

        if ($orderBy = $query->getOrderBy()) {
            $query->applyModifier($orderBy);
        }

        $source = $query->getSource();
        if (null !== $this->fields) {
            $filteredResult = [];

            foreach ($source as $item) {
                if (\is_string($this->fields)) {
                    $fieldsValues = $this->propertyAccessor->getValue($item, $this->fields);
                } else {
                    $fieldsValues = [];
                    foreach ($this->fields as $field) {
                        $fieldsValues[$field] = $this->propertyAccessor->getValue($item, $field);
                    }
                }

                $filteredResult[] = $fieldsValues;
            }

            $source = $filteredResult;
        }

        return $source;
    }
}
