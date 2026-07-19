<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine;

use App\Shared\Domain\Utils\IntegerHelper;
use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;

class QueryBuilder
{
    private array $aliases = [];

    public function __construct(private DoctrineQueryBuilder $queryBuilder, private string $alias)
    {
        $this->aliases['root'] = $alias;
    }

    public static function from(DoctrineQueryBuilder $queryBuilder): self
    {
        return new self($queryBuilder, $queryBuilder->getRootAliases()[0]);
    }

    public function equals(string $field, mixed $value, ?string $alias = null): self
    {
        $alias ??= $this->alias;

        if ($value !== null && $value !== '') {
            $this->queryBuilder->andWhere("{$alias}.{$field} = :{$field}")
                ->setParameter($field, $value);
        }

        return $this;
    }

    public function notEquals(string $field, mixed $value, ?string $alias = null): self
    {
        $alias ??= $this->alias;

        if ($value !== null && $value !== '') {
            $this->queryBuilder
                ->andWhere("{$alias}.{$field} != :{$field}")
                ->setParameter($field, $value);
        } elseif ($value === null) {
            $this->queryBuilder
                ->andWhere("{$alias}.{$field} IS NOT NULL");
        }

        return $this;
    }

    public function like(
        string $field,
        ?string $value,
        bool $insensitive = false,
        ?string $alias = null
    ): self {
        $alias ??= $this->alias;

        if (!empty($value)) {
            if ($insensitive) {
                $this->queryBuilder->andWhere("LOWER({$alias}.{$field}) LIKE LOWER(:{$field})");
            } else {
                $this->queryBuilder->andWhere("{$alias}.{$field} LIKE :{$field}");
            }

            $this->queryBuilder->setParameter($field, '%' . $value . '%');
        }

        return $this;
    }

    public function likeMultiple(
        array $fields,
        ?string $value,
        bool $insensitive = false,
        ?string $alias = null
    ): self {
        $alias ??= $this->alias;

        if (!empty($value)) {
            $orExpressions = [];
            foreach ($fields as $field) {
                if ($insensitive) {
                    $orExpressions[] = $this->queryBuilder->expr()->like(
                        "LOWER({$alias}.{$field})",
                        "LOWER(:search_value)"
                    );
                } else {
                    $orExpressions[] = $this->queryBuilder->expr()->like(
                        "{$alias}.{$field}",
                        ":search_value"
                    );
                }
            }

            if (!empty($orExpressions)) {
                $this->queryBuilder->andWhere(
                    $this->queryBuilder->expr()->orX(...$orExpressions)
                )->setParameter('search_value', '%' . $value . '%');
            }
        }

        return $this;
    }

    public function inCollection(string $field, mixed $value, ?string $alias = null): self
    {
        $alias ??= $this->alias;

        if ($value !== null && $value !== '') {
            $this->queryBuilder->andWhere(":{$field} MEMBER OF {$alias}.{$field}")
                ->setParameter($field, $value);
        }

        return $this;
    }

    public function greaterOrEqual(string $field, mixed $value, ?string $alias = null): self
    {
        $alias ??= $this->alias;

        if (!empty($value)) {
            $this->queryBuilder->andWhere("{$alias}.{$field} >= :{$field}")
                ->setParameter($field, $value);
        }

        return $this;
    }

    public function lessOrEqual(string $field, mixed $value, ?string $alias = null): self
    {
        $alias ??= $this->alias;

        if (!empty($value)) {
            $this->queryBuilder->andWhere("{$alias}.{$field} <= :{$field}")
                ->setParameter($field, $value);
        }

        return $this;
    }

    public function applyOrder(string $orderBy, string $order): self
    {
        $fields = array_map('trim', explode(',', $orderBy));

        foreach ($fields as $index => $field) {
            if (IntegerHelper::isEqual((int) $index, 0)) {
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

    public function random(): self
    {
        $this->queryBuilder
            ->addSelect('RANDOM() as HIDDEN rand')
            ->orderBy('rand', 'ASC');

        return $this;
    }

    public function in(string $field, array $values, ?string $alias = null): self
    {
        $alias ??= $this->alias;

        if (!empty($values)) {
            $this->queryBuilder->andWhere("{$alias}.{$field} IN (:{$field})")
                ->setParameter($field, $values);
        }

        return $this;
    }

    public function queryBuilder(): DoctrineQueryBuilder
    {
        return $this->queryBuilder;
    }

    public function join(
        string $relation,
        string $alias,
        string $type = 'inner'
    ): self {
        if ($type === 'left') {
            $this->queryBuilder->leftJoin(
                "{$this->aliases['root']}.{$relation}",
                $alias
            );
        } else {
            $this->queryBuilder->innerJoin(
                "{$this->aliases['root']}.{$relation}",
                $alias
            );
        }

        $this->aliases[$relation] = $alias;

        return $this;
    }

    public function leftJoin(string $relation, string $alias): self
    {
        return $this->join($relation, $alias, 'left');
    }

    public function innerJoin(string $relation, string $alias): self
    {
        return $this->join($relation, $alias);
    }
}
