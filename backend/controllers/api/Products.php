<?php

namespace controllers\api;

use http\Request;
use core\Validator;

class Products
{
    /**
     * Update product
     * @return bool
     */
    public static function update($id)
    {
        $id = intval($id);

        $form = Request::body();

        $validator = new Validator($form);

        $validator->field('name', 'Nome do Produto')->isRequired()->lengthMinMax(5, 255);
        $validator->field('price', 'Valor do Produto')->isRequired()->money(0.001, null, 3);
        $validator->field('id_type', 'Tipo do Produto')->isRequired()->numericMinMax(1);

        $validatedData = $validator->validated();

        $product = \models\Products::getById($id);

        $product->prd_id      = $id;
        $product->prd_name    = $validatedData['name'];
        $product->prd_price   = $validatedData['price'];
        $product->prd_id_type = $validatedData['id_type'];

        return $product->update();
    }

    /**
     * Store a new product
     * @return bool
     */
    public static function new()
    {
        $form = Request::body();

        $validator = new Validator($form);

        $validator->field('name', 'Nome do Produto')->isRequired()->lengthMinMax(5, 255);
        $validator->field('price', 'Valor do Produto')->isRequired()->money(0.001, null, 3);
        $validator->field('id_type', 'Tipo do Produto')->isRequired()->numericMinMax(1);

        $validatedData = $validator->validated();

        $product = new \models\Products();

        $product->prd_id_user = Auth::user()->usr_id;
        $product->prd_name    = $validatedData['name'];
        $product->prd_price   = $validatedData['price'];
        $product->prd_id_type = $validatedData['id_type'];

        return $product->store();
    }

    /**
     * Retrive all products
     * @return array<\models\Products>
     */
    public static function list()
    {
        $result = \models\Products::list();

        return $result;
    }

    /**
     * Get a product by ID
     * @return \models\Products
     */
    public static function getByID($id)
    {
        $id = intval($id);

        $result = \models\Products::getById($id);

        return $result;
    }
}