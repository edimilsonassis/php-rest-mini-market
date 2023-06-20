<?php

namespace controllers\api;

use http\Request;
use core\Validator;

class ProductsTypes
{
    /**
     * Update type
     * @return bool
     */
    public static function update($id)
    {
        $id = intval($id);

        $form = Request::body();

        $validator = new Validator($form);

        $validator->field('tax', 'Valor do Imposto')->isRequired()->money(0.001, null, 3);
        $validator->field('description', 'Descricao do Tipo')->isRequired()->lengthMinMax(1, 255);

        $validatedData = $validator->validated();

        $productType = \models\ProductsTypes::getById($id);

        $productType->tpo_id          = $id;
        $productType->tpo_tax         = $validatedData['tax'];
        $productType->tpo_description = $validatedData['description'];

        return $productType->update();
    }

    /**
     * CADASTRA UM NOVO TIPO
     * @return bool
     */
    public static function new()
    {
        $form = Request::body();

        $validator = new Validator($form);

        $validator->field('tax', 'Valor do Imposto')->isRequired()->money(0.001, null, 3);
        $validator->field('description', 'Descricao do Tipo')->isRequired()->lengthMinMax(5, 15);

        $validatedData = $validator->validated();

        $productType = new \models\ProductsTypes();

        $productType->tpo_id_user     = Auth::user()->usr_id;
        $productType->tpo_tax         = $validatedData['tax'];
        $productType->tpo_description = $validatedData['description'];

        return $productType->store();
    }

    /**
     * RETORNA UMA LISTA DOS TIPOS
     * @return array<\models\ProductsTypes>
     */
    public static function list()
    {
        $result = \models\ProductsTypes::list();

        return $result;
    }

    /**
     * RETORNA UM TIPO PELO ID
     * @return \models\ProductsTypes
     */
    public static function getByID($id)
    {
        $id = intval($id);

        $result = \models\ProductsTypes::getByID($id);

        return $result;
    }
}