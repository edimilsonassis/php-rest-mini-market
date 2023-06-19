<?php

namespace controllers\api;

use http\Exception;
use http\Request;
use core\Validator;

class SalesItems
{
    /**
     * Store a new sale item
     * @return bool
     */
    public static function new($id_sale)
    {
        $id = intval($id_sale);

        $form = Request::body();

        $validator = new Validator($form);

        $validator->field('id_product', 'ID da Produto')->isRequired()->numericMinMax(1);
        $validator->field('qtde', 'Quatidade')->isRequired()->numericMinMax(0.001);

        $validatedData = $validator->validated();

        // Validate the product
        if (!$product = \models\Products::getById($validatedData['id_product']))
            throw new Exception('The product does not exist!', 400);

        $salesItens = new \models\SalesItems();

        $salesItens->sls_ite_id_user     = Auth::user()->usr_id;
        $salesItens->sls_ite_id_sale     = $id;
        $salesItens->sls_ite_id_product  = $validatedData['id_product'];
        $salesItens->sls_ite_qtd         = $validatedData['qtde'];
        $salesItens->sls_ite_price       = $product->prd_price;
        $salesItens->sls_ite_description = $product->prd_name;
        $salesItens->sls_ite_tax         = $product->type->tpo_tax;

        return $salesItens->store();
    }

    /**
     * Returns a list of sale items
     * @return array
     */
    public static function list($id_sale)
    {
        $id = intval($id_sale);

        $result = \models\SalesItems::list($id);

        return $result;
    }
}