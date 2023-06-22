<?php

namespace routers;

use controllers\api;
use http\Response;

class MarketServices
{
    function __construct(string $url)
    {
        $router = \http\Router::getInstance($url);

        $router->get('/', [
            function () {
                return new Response(401, 'Inform a request');
            }
        ]);

        $router->post('auth/login', [
            'middleware' => [],
            function () {
                return new Response(200, api\Auth::login());
            }
        ]);

        $router->post('users', [
            'middleware' => [],
            function () {
                return new Response(200, api\Users::new());
            }
        ]);

        $router->get('users', [
            'middleware' => [
                'logged'
            ],
            function () {
                return new Response(200, api\Users::list());
            }
        ]);

        $router->put('users/{id}', [
            'middleware' => [
                'logged'
            ],
            function ($id) {
                return new Response(200, api\Users::update($id));
            }
        ]);

        $router->get('users/{id}', [
            'middleware' => [
                'logged'
            ],
            function ($id) {
                return new Response(200, api\Users::getByID($id));
            }
        ]);


        // Products routes
        $router->post('products', [
            'middleware' => [
                'logged'
            ],
            function () {
                return new Response(200, api\Products::new());
            }
        ]);

        $router->get('products', [
            'middleware' => [
                'logged'
            ],
            function () {
                return new Response(200, api\Products::list());
            }
        ]);

        $router->put('products/{id}', [
            'middleware' => [
                'logged'
            ],
            function ($id) {
                return new Response(200, api\Products::update($id));
            }
        ]);

        $router->get('products/{id}', [
            'middleware' => [
                'logged'
            ],
            function ($id) {
                return new Response(200, api\Products::getByID($id));
            }
        ]);

        // Products Types routes
        $router->post('types', [
            'middleware' => [
                'logged'
            ],
            function () {
                return new Response(200, api\ProductsTypes::new());
            }
        ]);

        $router->get('types', [
            'middleware' => [
                'logged'
            ],
            function () {
                return new Response(200, api\ProductsTypes::list());
            }
        ]);

        $router->put('types/{id}', [
            'middleware' => [
                'logged'
            ],
            function ($id) {
                return new Response(200, api\ProductsTypes::update($id));
            }
        ]);

        $router->get('types/{id}', [
            'middleware' => [
                'logged'
            ],
            function ($id) {
                return new Response(200, api\ProductsTypes::getByID($id));
            }
        ]);

        // Sales Items routes
        $router->post('sales/{id}/items', [
            'middleware' => [
                'logged'
            ],
            function ($id) {
                return new Response(200, api\SalesItems::new($id));
            }
        ]);

        $router->get('sales/{id}/items', [
            'middleware' => [
                'logged'
            ],
            function ($id) {
                return new Response(200, api\SalesItems::list($id));
            }
        ]);

        // Sales routes
        $router->post('sales', [
            'middleware' => [
                'logged'
            ],
            function () {
                return new Response(200, api\Sales::new());
            }
        ]);

        $router->get('sales/{id}', [
            'middleware' => [
                'logged'
            ],
            function ($id) {
                return new Response(200, api\Sales::getByID($id));
            }
        ]);

        $router->get('sales', [
            'middleware' => [
                'logged'
            ],
            function () {
                return new Response(200, api\Sales::list());
            }
        ]);

        $router->run()->display();
    }
}