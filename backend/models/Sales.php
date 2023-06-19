<?php

namespace models;

use http\Exception;
use core\DB;

class Sales extends Model
{
    use WithQuery;

    public ?int $sls_id = null;

    public ?string $sls_client = null;

    public ?string $sls_id_user = null;

    public ?float $sls_total_price = null;

    public ?float $sls_total_price_taxes = null;

    public ?string $sls_date = null;

    /* 
    /* @var array<SaleItems> $items
     */
    public ?array $items = null;


    public function withItems()
    {
        $this->items = SalesItems::list($this->sls_id);
    }

    /**
     * Get a sale by ID
     * @access public
     * @return self
     */
    public static function getByID(int $id)
    {
        $data = [
            'sls_id' => $id
        ];

        $result = self::query()
            ->where('"sls_id" = :sls_id')
            ->bind($data)
            ->limit(1)
            ->select()
            ->fetch(\PDO::FETCH_CLASS);

        if ($result)
            $result->withItems();

        return $result;
    }

    /**
     * Get an array with the sales list
     * @access public
     * @return array
     */
    public static function list()
    {
        $result = self::query()
            ->select()
            ->fetchAll(\PDO::FETCH_CLASS, self::class);

        return $result;
    }

    /**
     * Register a new sale
     * @access public
     * @return bool|string
     */
    public function store()
    {
        $data = [
            'sls_id_user'     => $this->sls_id_user,
            'sls_client'      => $this->sls_client,
            'sls_total_price' => $this->sls_total_price ?: 0,
        ];

        $result = self::query()
            ->bind($data)
            ->insert();

        return $result;
    }

    /**
     * Register a new sale ID
     * @access public
     * @return int
     */
    public static function genID()
    {
        $pdo = DB::getInstance();

        $sql = "SELECT NEXTVAL('sales_id_seq' :: REGCLASS)";

        $stmt = $pdo->query($sql);

        if (!$result = $stmt->fetch(2))
            throw new Exception('It was not possible to generate a new sale!', 500);

        return $result->nextval;
    }
}