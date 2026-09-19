<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder as DoctrineQueryBuilder;

class NativeQueryBuilder
{
    private DoctrineQueryBuilder $queryBuilder;

    public function __construct(
        Connection $connection,
        string $table,
        private string $alias,
    ) {
        $this->queryBuilder = $connection->createQueryBuilder()->from($table, $alias);
    }

    public static function from(Connection $connection, string $table, string $alias): self
    {
        return new self($connection, $table, $alias);
    }

    public function select(string ...$fields): self
    {
        $this->queryBuilder->select(...$fields);

        return $this;
    }

    public function equals(string $field, mixed $value, ?string $alias = null): self
    {
        $alias ??= $this->alias;

        if ($value !== null && $value !== '') {
            $this->queryBuilder
                ->andWhere("{$alias}.{$field} = :{$field}")
                ->setParameter($field, $value);
        }

        return $this;
    }

    public function greaterOrEqual(
        string $field,
        mixed $value,
        ?string $alias = null,
        ?string $parameter = null,
    ): self {
        $alias ??= $this->alias;
        $parameter ??= $field;

        if ($value !== null && $value !== '') {
            $this->queryBuilder
                ->andWhere("{$alias}.{$field} >= :{$parameter}")
                ->setParameter($parameter, $value);
        }

        return $this;
    }

    public function lessThan(
        string $field,
        mixed $value,
        ?string $alias = null,
        ?string $parameter = null,
    ): self {
        $alias ??= $this->alias;
        $parameter ??= $field;

        if ($value !== null && $value !== '') {
            $this->queryBuilder
                ->andWhere("{$alias}.{$field} < :{$parameter}")
                ->setParameter($parameter, $value);
        }

        return $this;
    }

    public function andWhere(string $condition): self
    {
        $this->queryBuilder->andWhere($condition);

        return $this;
    }

    public function setParameter(string $name, mixed $value): self
    {
        $this->queryBuilder->setParameter($name, $value);

        return $this;
    }

    public function innerJoin(
        string $table,
        string $alias,
        string $condition,
        ?string $fromAlias = null,
    ): self {
        $this->queryBuilder->innerJoin(
            $fromAlias ?? $this->alias,
            $table,
            $alias,
            $condition,
        );

        return $this;
    }

    public function leftJoin(
        string $table,
        string $alias,
        string $condition,
        ?string $fromAlias = null,
    ): self {
        $this->queryBuilder->leftJoin(
            $fromAlias ?? $this->alias,
            $table,
            $alias,
            $condition,
        );

        return $this;
    }

    public function groupBy(string ...$fields): self
    {
        $this->queryBuilder->groupBy(...$fields);

        return $this;
    }

    public function having(string $condition): self
    {
        $this->queryBuilder->having($condition);

        return $this;
    }

    public function orderBy(string $field, string $order): self
    {
        $this->queryBuilder->orderBy($field, $order);

        return $this;
    }

    public function addOrderBy(string $field, string $order): self
    {
        $this->queryBuilder->addOrderBy($field, $order);

        return $this;
    }

    public function applyOrder(string $orderBy, string $order): self
    {
        foreach (array_map('trim', explode(',', $orderBy)) as $index => $field) {
            if ($index === 0) {
                $this->queryBuilder->orderBy("{$this->alias}.{$field}", $order);
            } else {
                $this->queryBuilder->addOrderBy("{$this->alias}.{$field}", $order);
            }
        }

        return $this;
    }

    public function paginate(?int $limit, ?int $offset): self
    {
        if ($limit) {
            $this->queryBuilder->setMaxResults($limit);
        }

        if ($offset) {
            $this->queryBuilder->setFirstResult($offset);
        }

        return $this;
    }

    public function maxResults(int $max): self
    {
        $this->queryBuilder->setMaxResults($max);

        return $this;
    }

    public function fetchAssociative(): ?array
    {
        $result = $this->queryBuilder->executeQuery()->fetchAssociative();

        return is_array($result) ? $result : null;
    }

    public function fetchAllAssociative(): array
    {
        return $this->queryBuilder->executeQuery()->fetchAllAssociative();
    }

    public function fetchOne(): mixed
    {
        return $this->queryBuilder->executeQuery()->fetchOne();
    }

    public function queryBuilder(): DoctrineQueryBuilder
    {
        return $this->queryBuilder;
    }
}
