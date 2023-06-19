<?php

namespace controllers\api;

use core\Validator;
use http\Request;

class Sales
{
    /**
     * Store a new sale
     * @return bool
     */
    public static function new()
    {
        $form = Request::body();

        $validator = new Validator($form);

        $validator->field('client', 'CPF do Cliente')->lengthMinMax(5, 20);

        $validatedData = $validator->validated();

        $sale = new \models\Sales();

        $sale->sls_id_user = Auth::user()->usr_id;
        $sale->sls_client  = $validatedData['client'] ?? 'Cliente sem CPF';

        return $sale->store();
    }

    /**
     * Generate a new sale ID
     * @return bool
     */
    public static function genID()
    {
        $result = \models\Sales::genID();

        return $result;
    }

    /**
     * Retrives a product list
     * @return array<\models\Sales>
     */
    public static function list()
    {
        $result = \models\Sales::list();

        return $result;
    }

    /**
     * Get a sale by ID
     * @return \models\Sales
     */
    public static function getByID($id)
    {
        $id = intval($id);

        $result = \models\Sales::getByID($id);

        if ($result)
            $result->withItems();

        return $result;
    }
}