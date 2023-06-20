<?php

namespace models;

use http\Exception;

class Products extends Model
{
    use WithQuery;

    public ?int $prd_id = null;

    public ?int $prd_id_type = null;

    public ?int $prd_id_user = null;

    public ?string $prd_name = null;

    public ?float $prd_price = null;

    public ?ProductsTypes $type = null;


    public function withType()
    {
        $this->type = ProductsTypes::getByID($this->prd_id_type);
    }

    /**
     * Get a product
     * @access public
     * @return self
     */
    public static function getById(int $id)
    {
        $data = [
            'prd_id' => $id
        ];

        $result = self::query()
            ->where('"prd_id" = :prd_id')
            ->bind($data)
            ->limit(1)
            ->select()
            ->fetch(\PDO::FETCH_CLASS);

        if ($result)
            $result->withType();

        return $result;
    }

    /**
     * Get an array with the list of products
     * @access public
     * @return array<self>
     */
    public static function list()
    {
        $result = self::query()
            ->orderBy('"prd_id" DESC')
            ->select()
            ->fetchAll(\PDO::FETCH_CLASS, self::class);

        array_walk($result, function (self $item) {
            $item->withType();
        });

        return $result;
    }

    /**
     * Register a new product
     * @access public
     * @return bool|string 
     */
    public function store()
    {
        $data = [
            'prd_id_type' => $this->prd_id_type,
            'prd_id_user' => $this->prd_id_user,
            'prd_name'    => $this->prd_name,
            'prd_price'   => $this->prd_price,
        ];

        $result = self::query()
            ->bind($data)
            ->insert();

        return $result;
    }

    /**
     * Update a product by ID
     * @access public
     * @return int
     */
    public function update()
    {
        $data = [
            'prd_id'      => $this->prd_id,
            'prd_id_type' => $this->prd_id_type,
            'prd_id_user' => $this->prd_id_user,
            'prd_name'    => $this->prd_name,
            'prd_price'   => $this->prd_price,
        ];

        return self::query()
            ->where('"prd_id" = :prd_id')
            ->bind($data)
            ->update()
            ->rowCount();
    }
}