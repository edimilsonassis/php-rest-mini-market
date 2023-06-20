<?php

namespace controllers\api;

use http\Request;
use http\Exception;
use core\Validator;

class Users
{
    /**
     * Update user
     * @return bool
     */
    public static function update($id)
    {
        $id = intval($id);

        $form = Request::body();

        $validator = new Validator($form);

        $validator->field('name', 'Nome do Completo')->isRequired()->lengthMinMax(5, 255);
        $validator->field('username', 'Usuário')->isRequired()->lengthMinMax(3, 20);
        $validator->field('password', 'Senha')->isRequired()->lengthMinMax(6, 255);

        $validatedData = $validator->validated();

        $user = \models\Users::getById($id);

        if ($user->getByUsername($validatedData['username']) and $user->usr_id != $id) {
            throw new Exception('User already exists');
        }

        $user->usr_id       = $id;
        $user->urs_name     = $validatedData['name'];
        $user->urs_username = $validatedData['username'];
        $user->urs_password = $validatedData['password'];

        return $user->update();
    }

    /**
     * Store a new user
     * @return bool
     */
    public static function new()
    {
        $form = Request::body();

        $validator = new Validator($form);

        $validator->field('name', 'Nome do Completo')->isRequired()->lengthMinMax(1, 255);
        $validator->field('username', 'Usuário')->isRequired()->lengthMinMax(1, 20);
        $validator->field('password', 'Senha')->isRequired()->lengthMinMax(1, 255);

        $validatedData = $validator->validated();

        $user = new \models\Users();

        if ($user->getByUsername($validatedData['username'])) {
            throw new Exception('User already exists');
        }

        $user->urs_name     = $validatedData['name'];
        $user->urs_username = $validatedData['username'];
        $user->urs_password = $validatedData['password'];

        return $user->store();
    }

    /**
     * Retrive all products
     * @return array<\models\Users>
     */
    public static function list()
    {
        $result = \models\Users::list();

        return $result;
    }

    /** 
     * Get a user by ID
     * @return \models\Users
     */
    public static function getByID($id)
    {
        $id = intval($id);

        $result = \models\Users::getById($id);

        return $result;
    }
}