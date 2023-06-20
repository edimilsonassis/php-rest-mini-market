<?php

namespace controllers;

use controllers\api\ProductsTypes;
use core\WithViews;

class App extends WithViews
{
    public static function index()
    {
        $data = [
            'pageTitle' => 'Dashboard',
        ];

        return self::view('index', $data);
    }

    public static function login()
    {
        $data = [
            'pageTitle' => 'Login',
        ];

        return self::view('login', $data);
    }

    public static function pdv()
    {
        $data = [
            'pageTitle' => 'PDV',
        ];

        return self::view('pdv', $data);
    }

    public static function sales()
    {
        $data = [
            'pageTitle' => 'Vendas',
        ];

        return self::view('sales', $data);
    }

    public static function product($id)
    {
        $product = $id == 'novo' ? null : \models\Products::getById(intval($id));

        $data = [
            'pageTitle' => $product ? "$product->prd_name" : 'Novo Produto',
            'types'     => \models\ProductsTypes::list(),
            'product'   => $product
        ];

        return self::view('product', $data);
    }

    public static function products()
    {
        $data = [
            'pageTitle' => 'Produtos',
        ];

        return self::view('products', $data);
    }

    public static function type($id)
    {
        $type = $id == 'novo' ? null : \models\ProductsTypes::getById(intval($id));

        $data = [
            'pageTitle' => $type ? "$type->tpo_description" : 'Novo Tipo',
            'type'      => $type
        ];

        return self::view('type', $data);
    }

    public static function types()
    {
        $data = [
            'pageTitle' => 'Tipos',
        ];

        return self::view('types', $data);
    }

    public static function user($id)
    {
        $user = $id == 'novo' ? null : \models\Users::getById(intval($id));

        $data = [
            'pageTitle' => $user ? "$user->urs_name" : 'Novo Usuario',
            'user'      => $user
        ];

        return self::view('user', $data);
    }

    public static function users()
    {
        $data = [
            'pageTitle' => 'Usuários',
        ];

        return self::view('users', $data);
    }
}