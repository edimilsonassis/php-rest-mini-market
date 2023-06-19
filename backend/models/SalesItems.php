<?php

namespace models;

class SalesItems extends Model
{
    use WithQuery;

    public ?int $sls_ite_id = null;

    public ?int $sls_ite_id_sale = null;

    public ?int $sls_ite_id_user = null;

    public ?int $sls_ite_id_product = null;

    public ?string $sls_ite_description = null;

    public ?float $sls_ite_price = null;

    public ?float $sls_ite_qtd = null;

    public ?float $sls_ite_total = null;

    public ?float $sls_ite_tax = null;


    /**
     * Get an array with the sales list
     * @access public
     * @return array
     */
    public static function list(int $id_venda)
    {
        $data = [
            'sls_ite_id_sale' => $id_venda
        ];

        $result = self::query()
            ->where('"sls_ite_id_sale" = :sls_ite_id_sale')
            ->orderBy('"sls_ite_id" desc')
            ->bind($data)
            ->select()
            ->fetchAll(\PDO::FETCH_CLASS, self::class);

        return $result;
    }

    /**
     * Register a new selling item
     * @access public
     * @return bool|string
     */
    public function store()
    {
        $data = [
            'sls_ite_id_product'  => $this->sls_ite_id_product,
            'sls_ite_id_sale'     => $this->sls_ite_id_sale,
            'sls_ite_description' => $this->sls_ite_description,
            'sls_ite_price'       => $this->sls_ite_price,
            'sls_ite_qtd'         => $this->sls_ite_qtd,
            'sls_ite_total'       => $this->sls_ite_qtd * $this->sls_ite_price,
            'sls_ite_tax'         => $this->sls_ite_tax,
        ];

        $result = self::query()
            ->bind($data)
            ->insert();

        return $result;
    }
}