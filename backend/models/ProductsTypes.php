<?php

namespace models;

class ProductsTypes extends Model
{
    use WithQuery;

    public ?int $tpo_id = null;

    public ?int $tpo_id_user = null;

    public ?string $tpo_description = null;

    public ?float $tpo_tax = null;


    /**
     * Get a type by ID
     * @access public
     * @return self
     */
    public static function getByID(int $id)
    {
        $data = [
            'tpo_id' => $id
        ];

        $result = self::query()
            ->where('"tpo_id" = :tpo_id')
            ->bind($data)
            ->limit(1)
            ->select()
            ->fetch(\PDO::FETCH_CLASS);

        return $result;
    }

    /**
     * Get an array with the type list
     * @access public
     * @return array<self>
     */
    public static function list()
    {
        $result = self::query()
            ->select()
            ->fetchAll(\PDO::FETCH_CLASS, self::class);

        return $result;
    }

    /**
     * Register a new type
     * @access public
     * @return bool|string
     */
    public function store()
    {
        $data = [
            'tpo_tax'         => $this->tpo_tax,
            'tpo_id_user'     => $this->tpo_id_user,
            'tpo_description' => $this->tpo_description,
        ];

        $result = self::query()
            ->bind($data)
            ->insert();

        return $result;
    }

    /**
     * Update a type by ID
     * @access public
     * @return int
     */
    public function update()
    {
        $data = [
            'tpo_id'          => $this->tpo_id,
            'tpo_id_user'     => $this->tpo_id_user,
            'tpo_tax'         => $this->tpo_tax,
            'tpo_description' => $this->tpo_description,
        ];

        return self::query()
            ->where('"prd_id" = :prd_id')
            ->bind($data)
            ->update()
            ->rowCount();
    }
}