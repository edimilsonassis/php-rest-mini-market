<?php

namespace core;

use http\Exception;

class Query
{
    public string $table = "";

    public ?string $class = null;

    private ?array $collums = [
        "*"
    ];

    private ?array $binds = [];

    private ?array $where = null;

    private ?array $orderBy = null;

    private ?int $limit = null;


    public function __construct(string $table, string $class)
    {
        $this->reset();
        $this->class = $class;
        $this->table = strtolower($table);
    }

    public function collums(...$collums)
    {
        $this->collums = $collums;
        return $this;
    }

    public function where(...$where)
    {
        $this->where = $where;
        return $this;
    }

    public function orderBy(...$orderBy)
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    public function bind(array $binds)
    {
        $this->binds = $binds;
        return $this;
    }

    public function limit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    private function reset()
    {
        $this->collums = ['*'];
        $this->where   = null;
        $this->orderBy = null;
        $this->limit   = null;

        return $this;
    }

    public function select()
    {
        $pdo     = DB::getInstance();
        $collums = implode(', ', $this->collums);

        $query = "SELECT $collums FROM $this->table";

        if (!empty($this->where)) {
            $where = implode(' AND ', $this->where);
            $query .= " WHERE $where";
        }

        if (!empty($this->orderBy)) {
            $orderBy = implode(', ', $this->orderBy);
            $query .= " ORDER BY $orderBy";
        }

        if (!empty($this->limit)) {
            $limit = $this->limit;
            $query .= " LIMIT $limit";
        }

        $stmt = $pdo->prepare($query);

        if (!$stmt or !$stmt->execute($this->binds)) {
            throw new Exception('Failed to execute the query select.', 500);
        }

        if ($this->class) {
            $stmt->setFetchMode(\PDO::FETCH_CLASS, $this->class);
        }

        return $stmt;
    }

    public function update()
    {
        $pdo = DB::getInstance();

        $sets     = [];
        $bindings = [];

        foreach ($this->binds as $column => $value) {
            $sets[]     = "$column = :$column";
            $bindings[] = $value;
        }

        $setClause   = implode(', ', $sets);
        $whereClause = implode(' AND ', $this->where);

        $query = "UPDATE $this->table SET $setClause WHERE $whereClause";

        $stmt = $pdo->prepare($query);

        if (!$stmt or !$stmt->execute($bindings)) {
            throw new Exception('Failed to execute the query update.', 500);
        }

        return $stmt;
    }

    public function insert()
    {
        $pdo = DB::getInstance();

        $keys    = array_keys($this->binds);
        $collums = implode(', ', $keys);
        $vars    = implode(', :', $keys);

        $sql = "INSERT INTO $this->table ( $collums ) VALUES ( :$vars );";

        $stmt = $pdo->prepare($sql);

        if (!$stmt->execute($this->binds)) {
            throw new Exception('Failed to execute the query insert.', 500);
        }

        return $pdo->lastInsertId();
    }
}