<?php

namespace routers;

use controllers\App;
use http\Response;

class MarketPages
{
    function __construct(string $url)
    {
        $router = \http\Router::getInstance($url);

        $router->get('', [
            function () {
                return new Response(200, App::index(), Response::RPS_CT_HTML);
            }
        ]);

        $router->get('/login', [
            function () {
                return new Response(200, App::login(), Response::RPS_CT_HTML);
            }
        ]);

        $router->get('/pdv', [
            function () {
                return new Response(200, App::pdv(), Response::RPS_CT_HTML);
            }
        ]);

        $router->get('/vendas', [
            function () {
                return new Response(200, App::sales(), Response::RPS_CT_HTML);
            }
        ]);

        $router->get('/produtos/{id}', [
            function ($id) {
                return new Response(200, App::product($id), Response::RPS_CT_HTML);
            }
        ]);

        $router->get('/produtos', [
            function () {
                return new Response(200, App::products(), Response::RPS_CT_HTML);
            }
        ]);

        $router->get('/tipos', [
            function () {
                return new Response(200, App::types(), Response::RPS_CT_HTML);
            }
        ]);

        $router->get('/usuarios', [
            function () {
                return new Response(200, App::users(), Response::RPS_CT_HTML);
            }
        ]);

        $router->run()->display();
    }
}