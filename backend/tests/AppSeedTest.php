<?php

namespace tests;

use controllers\api\Auth;
use \models;

use GuzzleHttp\Client;
use models\Users;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../autoload.php';

class AppSeedTest extends TestCase
{
    public function test_create_new_user()
    {
        if (!Users::getByUsername('edimilson')) {
            $client = new Client();

            $data = [
                'urs_name'     => 'Edimilson Assis',
                'urs_username' => 'edimilson',
                'urs_password' => 'senha@123',
            ];

            $response = $client->post('http://localhost:8080/api/users', [
                'json' => $data
            ]);

            $this->assertEquals(200, $response->getStatusCode());
        }

        $this->assertTrue(true);
    }

    public function test_find_user_edimilson()
    {
        $user = Users::getByUsername('edimilson')->toArray();

        $data = [
            'usr_id'       => 1,
            'urs_name'     => 'Edimilson Assis',
            'urs_username' => 'edimilson',
            'urs_password' => 'senha@123',
        ];

        $this->assertEquals($user, $data);
    }

    public function test_create_products_types()
    {
        $data = [
            [
                'description' => 'Hortifruti',
                'tax'         => 31.5,
            ],
            [
                'description' => 'Limpeza',
                'tax'         => 32,
            ],
            [
                'description' => 'Açougue',
                'tax'         => 45.34,
            ],
            [
                'description' => 'Padaria',
                'tax'         => 25,
            ],
            [
                'description' => 'Utensílios',
                'tax'         => 25.3,
            ],
            [
                'description' => 'Frios',
                'tax'         => 31.12,
            ],
            [
                'description' => 'Doces',
                'tax'         => 42,
            ],
            [
                'description' => 'Hortifrut',
                'tax'         => 8
            ],
        ];

        $token = Auth::setLoggin(Users::getById(1))->jwt;

        foreach ($data as $item) {
            $client = new Client();

            $response = $client->post('http://localhost:8080/api/types', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ],
                'json'    => $item
            ]);

            $this->assertEquals(200, $response->getStatusCode());
        }
    }

    public function test_create_products()
    {
        $data = [
            [
                'id_type' => 3,
                'name'    => 'Ponta de Peito',
                'price'   => 28.9,
            ],
            [
                'id_type' => 2,
                'name'    => 'Pão Francês',
                'price'   => 32.99,
            ],
            [
                'id_type' => 1,
                'name'    => 'Rúcula',
                'price'   => 5.45,
            ],
            [
                'id_type' => 1,
                'name'    => 'Repolho',
                'price'   => 156.932,
            ],
            [
                'id_type' => 1,
                'name'    => 'Maracujá',
                'price'   => 18.35,
            ],
            [
                'id_type' => 1,
                'name'    => 'Testes',
                'price'   => 15.23,
            ],
            [
                'id_type' => 1,
                'name'    => 'Testes (Atualizado)',
                'price'   => 15.3,
            ],
            [
                'id_type' => 1,
                'name'    => 'Testes',
                'price'   => 15.23,
            ],
        ];

        $token = Auth::setLoggin(Users::getById(1))->jwt;

        foreach ($data as $item) {
            $client = new Client();

            $response = $client->post('http://localhost:8080/api/products', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token
                ],
                'json'    => $item
            ]);

            $this->assertEquals(200, $response->getStatusCode());
        }
    }

}