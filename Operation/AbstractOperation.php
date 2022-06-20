<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery\Operation;

use ObjectQuery\ObjectQuery;
use ObjectQuery\ObjectQueryContext;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

abstract class AbstractOperation implements OperationInterface
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

    protected function applySelect(array $source, ObjectQueryContext $context): array
    {
        if ($where = $this->parentQuery->getWhere()) {
            $source = $where->apply($source, $context);
        }

        if ($orderBy = $this->parentQuery->getOrderBy()) {
            $source = $orderBy->apply($source, $context);
        }

        if ($offset = $this->parentQuery->getOffset()) {
            $source = $offset->apply($source, $context);
        }

        if ($limit = $this->parentQuery->getLimit()) {
            $source = $limit->apply($source, $context);
        }

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
