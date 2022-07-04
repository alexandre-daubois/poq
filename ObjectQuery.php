<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery;

use ObjectQuery\Exception\AliasAlreadyTakenInQueryContextException;
use ObjectQuery\Exception\IncompatibleCollectionException;
use ObjectQuery\Modifier\Limit;
use ObjectQuery\Modifier\Offset;
use ObjectQuery\Modifier\OrderBy;
use ObjectQuery\Modifier\Where;
use ObjectQuery\Operation\Average;
use ObjectQuery\Operation\Concat;
use ObjectQuery\Operation\Count;
use ObjectQuery\Operation\Each;
use ObjectQuery\Operation\Max;
use ObjectQuery\Operation\Min;
use ObjectQuery\Operation\Select;
use ObjectQuery\Operation\SelectMany;
use ObjectQuery\Operation\SelectOne;
use ObjectQuery\Operation\Sum;

class ObjectQuery implements QueryInterface
{
    private iterable $source;
    private string $sourceAlias;

    private ?Where $where = null;
    private ?OrderBy $orderBy = null;
    private ?Limit $limit = null;
    private ?Offset $offset = null;

    private ObjectQueryContext $context;

    private ?ObjectQuery $subQuery = null;

    private function __construct(iterable $source, string $alias = '_', ObjectQueryContext $context = null)
    {
        $this->source = $source;
        $this->sourceAlias = $alias;
        $this->context = $context;
    }

    public static function from(iterable $source, QueryContextInterface $context = null, string $alias = '_'): QueryInterface
    {
        if (null !== $context && !$context instanceof ObjectQueryContext) {
            throw new \InvalidArgumentException(\sprintf('Context of class %s is not compatible with ObjectQuery.', $context::class));
        }

        $context ??= new ObjectQueryContext();

        if ($context->isUsedAlias($alias)) {
            throw new AliasAlreadyTakenInQueryContextException($alias);
        }

        foreach ($source as $item) {
            if (!\is_object($item)) {
                throw new IncompatibleCollectionException('from', 'Mixed and scalar collections are not supported. Collection must only contain objects to be used by ObjectQuery');
            }
        }

        return new ObjectQuery($source, $alias, $context->withUsedAlias($alias));
    }

    public function where(callable $callback): ObjectQuery
    {
        if ($this->subQuery) {
            return $this->subQuery->where($callback);
        }

        $this->where = new Where($this, $callback);

        return $this;
    }

    public function orderBy(ObjectQueryOrder $order, ?string $field = null): ObjectQuery
    {
        if ($this->subQuery) {
            return $this->subQuery->orderBy($order, $field);
        }

        $this->orderBy = new OrderBy($this, $order, $field);

        return $this;
    }

    public function limit(?int $limit): ObjectQuery
    {
        if ($this->subQuery) {
            return $this->subQuery->limit($limit);
        }

        $this->limit = new Limit($this, $limit);

        return $this;
    }

    public function offset(?int $offset): ObjectQuery
    {
        if ($this->subQuery) {
            return $this->subQuery->offset($offset);
        }

        $this->offset = new Offset($this, $offset);

        return $this;
    }

    public function selectMany(string $field, ?string $alias = '_'): ObjectQuery
    {
        if ($this->subQuery) {
            $this->subQuery->selectMany($field, $alias);

            return $this;
        }

        $this->subQuery = (new SelectMany($this, $field, $alias))
            ->apply($this);

        return $this;
    }

    public function select(array|string|null $fields = null): array
    {
        return $this->applyOperation(new Select($this, $fields));
    }

    public function selectOne(string|null $fields = null): mixed
    {
        return $this->applyOperation(new SelectOne($this, $fields));
    }

    public function count(): int
    {
        return $this->applyOperation(new Count($this));
    }

    public function concat(string $separator = ' ', ?string $field = null): string
    {
        return $this->applyOperation(new Concat($this, $field, $separator));
    }

    public function each(callable $callback): array
    {
        return $this->applyOperation(new Each($this, $callback));
    }

    public function max(?string $field = null): mixed
    {
        return $this->applyOperation(new Max($this, $field));
    }

    public function min(?string $field = null): mixed
    {
        return $this->applyOperation(new Min($this, $field));
    }

    public function average(?string $field = null): float
    {
        return $this->applyOperation(new Average($this, $field));
    }

    public function sum(?string $field = null): int|float
    {
        return $this->applyOperation(new Sum($this, $field));
    }

    public function getSourceAlias(): string
    {
        return $this->sourceAlias;
    }

    public function getWhere(): ?Where
    {
        return $this->where;
    }

    public function getOrderBy(): ?OrderBy
    {
        return $this->orderBy;
    }

    public function getLimit(): ?Limit
    {
        return $this->limit;
    }

    public function getOffset(): ?Offset
    {
        return $this->offset;
    }

    public function getContext(): ObjectQueryContext
    {
        return $this->context;
    }

    public function getSource(): iterable
    {
        return $this->source;
    }

    public function applyOperation(QueryOperationInterface $operation): mixed
    {
        if ($this->subQuery) {
            return $this->subQuery->applyOperation($operation);
        }

        return $operation->apply($this);
    }

    public function applyModifier(QueryModifierInterface $modifier): QueryInterface
    {
        if ($this->subQuery) {
            return $this->subQuery->applyModifier($modifier);
        }

        $this->source = $modifier->apply($this);

        return $this;
    }
}
