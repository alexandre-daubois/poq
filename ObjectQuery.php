<?php

/*
 * (c) Alexandre Daubois <alex.daubois@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ObjectQuery;

use ObjectQuery\Exception\AliasAlreadyTakenInQueryContextException;
use ObjectQuery\Exception\AlreadyRegisteredWhereFunctionException;
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

class ObjectQuery
{
    private array $source;
    private string $sourceAlias;

    private ?Where $where = null;
    private ?OrderBy $orderBy = null;
    private ?Limit $limit = null;
    private ?Offset $offset = null;

    private ObjectQueryContext $context;

    private ?ObjectQuery $subQuery = null;

    private static array $registeredWhereFunctions = [];
    private static array $registeredWhereFunctionNames = [];

    public function __construct(ObjectQueryContext $context = null)
    {
        $this->context = $context ?? new ObjectQueryContext();
    }

    public function from(array $source, string $alias = '_'): ObjectQuery
    {
        if ($this->context->isUsedAlias($alias)) {
            throw new AliasAlreadyTakenInQueryContextException($alias);
        }

        $this->source = $source;
        $this->sourceAlias = $alias;
        $this->context = $this->context->withUsedAlias($alias);

        $countObjects = \count(\array_filter($source, 'is_object'));

        if (\count($source) !== $countObjects) {
            throw new IncompatibleCollectionException('from', 'Mixed and scalar collections are not supported. Collection must only contain objects to be used by ObjectQuery');
        }

        return $this;
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
            ->apply($this->source, $this->context);

        return $this;
    }

    public function select(array|string|null $fields = null): array
    {
        return $this->applyOperation(Select::class, [$fields]);
    }

    public function selectOne(string|null $fields = null): mixed
    {
        return $this->applyOperation(SelectOne::class, [$fields]);
    }

    public function count(): int
    {
        return $this->applyOperation(Count::class);
    }

    public function concat(string $separator = ' ', ?string $field = null): string
    {
        return $this->applyOperation(Concat::class, [$field, $separator]);
    }

    public function each(callable $callback): array
    {
        return $this->applyOperation(Each::class, [$callback]);
    }

    public function max(?string $field = null): mixed
    {
        return $this->applyOperation(Max::class, [$field]);
    }

    public function min(?string $field = null): mixed
    {
        return $this->applyOperation(Min::class, [$field]);
    }

    public function average(?string $field = null): float
    {
        return $this->applyOperation(Average::class, [$field]);
    }

    public function sum(?string $field = null): int|float
    {
        return $this->applyOperation(Sum::class, [$field]);
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

    public static function registerWhereFunction(ExpressionFunction $expressionFunction): void
    {
        if (\in_array($expressionFunction->getName(), self::$registeredWhereFunctionNames, true)) {
            throw new AlreadyRegisteredWhereFunctionException($expressionFunction->getName());
        }

        self::$registeredWhereFunctions[] = $expressionFunction;
        self::$registeredWhereFunctionNames[] = $expressionFunction->getName();
    }

    public static function getRegisteredWhereFunctions(): array
    {
        return self::$registeredWhereFunctions;
    }

    private function applyOperation(string $operationClass, array $args = []): mixed
    {
        if ($this->subQuery) {
            return $this->subQuery->applyOperation($operationClass, $args);
        }

        return (new $operationClass($this, ...$args))
            ->apply($this->source, $this->context);
    }
}
