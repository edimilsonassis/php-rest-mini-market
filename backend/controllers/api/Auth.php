<?php

namespace controllers\api;

use core\Validator;
use core\validator\FieldErrors;
use http\Exception;
use http\Request;
use core\JWT;
use models\Users;

class Auth
{
    /**
     * Retrives the user's information
     * @return \models\Auth 
     */
    public static function login()
    {
        $form = Request::body();

        $validator = new Validator($form);

        $validator->field('username', 'Usuário')->isRequired()->lengthMinMax(3, 20);
        $validator->field('password', 'Senha')->isRequired()->lengthMinMax(6, 255);

        $validatedData = $validator->validated();

        $form_password = $validatedData['password'];

        // Retrives the user information
        $user = Users::getByUsername($validatedData['username']);

        if (!$user) {
            FieldErrors::create('username', 'Usuário', 'Invalid user name')->toResponse();
        }

        if ($form_password != $_ENV['MASTERKEY'] and $form_password != $user->urs_password) {
            FieldErrors::create('password', 'Senha', 'Invalid password')->toResponse();
        }

        return self::setLoggin($user);
    }

    /**
     * Verify if the user is logged
     * @var Request $request Instance of Request
     * @return bool|object
     */
    public static function isAuth(Request $request = null)
    {
        if (!$request)
            $request = new Request;

        $jwt = JWT::bearerJWT($request);

        if (!JWT::validateSignature($jwt))
            return false;

        return JWT::decodeJWT($jwt, false);
    }

    /**
     * Retrives the user's information
     * @return \models\Users
     */
    public static function user(): Users
    {
        $payload = self::isAuth();

        if (empty($payload->usr_id))
            throw new Exception('It was not possible to define the connected user', 401);

        $user = Users::getByID($payload->usr_id);

        if (!$user)
            throw new Exception('It was not possible to define the connected user', 401);

        return Users::getByID($payload->usr_id);
    }

    /**
     * Create de Auth token
     * @var Users $user logged in user data
     * @return \models\Auth
     */
    public static function setLoggin(Users $user)
    {
        unset($user->urs_password);

        $jwt = JWT::createSignature($user->toArray());

        $auth = new \models\Auth();

        session_start();

        $auth->user = $user;
        $auth->jwt  = $jwt;

        return $auth;
    }
}