## Resumo

Este projeto consiste no desenvolvimento de um mini framework de backend em PHP, que manipula os dados em um banco PostgreSQL (Há arquivo SQL na pasta com o backup da estrutura).

Todas as rotas Backend estão funcionais. Veja abaixo no Postman.

Há uma versão do frontend feita em `NextJS` + `TailwindCSS` na pasta `frontend`. As requisições são feitas via HTTP para o backend.
O frontend em uso foi feito usando PHP.

A ideia foi fazer um exemplo de um backend em PHP que funcione como uma API, com suporte a `JWT` e `Middlewares`, para controlar permissões de usuários, manutenção das rotas e alta escalabilidade.

![Tela de Ponto de Venda](https://github.com/edimilsonassis/php-rest-mini-market/blob/71439d61b1865703140ca622d1410a93aed0ee1f/screenshots/php/localhost_8080_pdv.png)

## Observações

- Eu gostaria de ter incluído uma lista dinâmica para selecionar o "Tipo" no formulário de "Cadastro de Produtos". Atualmente, utilizo um simples elemento `<select>`, que não proporciona muita interatividade. Com uma lista dinâmica, teria a capacidade de trazer outros dados de forma mais prática, como o imposto associado a cada tipo, e exibi-los em um local específico da tela.

## Começando

Primeiro instale as dependencias do backend

```bash
# acesse a pasta do projeto
cd /backend
# rode o composer
composer install
```

Inicialize o Servidor Backend pela pasta '/backend/public' usando o comando

```bash
# acesse a pasta publica do projeto
cd /backend/public
# rode o servidor interno do PHP
C:\PHP\php.exe -S localhost:8080
```

Está incluso uma versão do PHP 8.2.1 usado no desenvolvimento, mas deverá funcionar no PHP 8+

Para rodar o frontend (V1) basta usar um dos comandos na pasta '/frontend':

```bash
# instalar
npm install
# e depois
npm run dev
# ou
yarn dev
# ou
pnpm dev
```

Navegue até [http://localhost:3000](http://localhost:3000) com seu navegador para ver o resultado.

## Testes via Postman

Se preferir, existem testes para todas as rotas

<https://www.postman.com/restless-equinox-800919/workspace/market/collection/17260296-e654e775-bdb1-4d95-a7e5-1e6656666978?action=share&creator=17260296>

## Demonstração de Rotas do Backend
  
GET, POST atuam como UPDATE ou INSERT dependendo da rota.

Listar ou inserir um novo usuário
<http://localhost:8080/api/api/users>

Efetuar Login
<http://localhost:8080/api/auth/login>

Listar ou inserir um novo tipo
<http://localhost:8080/api/types>

Ver ou atualizar um tipo
<http://localhost:8080/api/types/1>

Listar ou inserir uma nova venda
<http://localhost:8080/api/sales>

Ver ou atualizar uma venda
<http://localhost:8080/api/sales/2>

Listar ou inserir um item na venda
<http://localhost:8080/api/sales/2/items>

Listar ou inserir um novo produto
<http://localhost:8080/api/products>

Ver ou atualizar um produto
<http://localhost:8080/api/products/2>

## Para as rotas POST de inserção

JSON para AUTH

```bash
# NOVO AUTH
{
    "urs_username": "edimilson",
    "urs_password": "senha@123"
}
```

JSON para inserir novo Produto

```bash
# NOVO PRODUTO
{
    "name": "Maracujá",
    "price": 15.23,
    "id_type": 1
}
```

JSON para inserir novo TIPO

```bash
# NOVO TIPO
{
    "description": "Hortifrut",
    "tax": 8
}
```

JSON para inserir novo USUARIO

```bash
# NOVO USUARIO
{
    "urs_name": "edimilson",
    "urs_username": "edimilson3",
    "urs_password": "senha@123"
}
```

JSON para inserir nova Venda

```bash
# NOVA VENDA
{
    "client": "42109479892"
}
```

JSON para inserir novo Item da Venda

```bash
# NOVO ITEM DA VENDA
{
    "id_product": 1,
    "qtde": 1
}
```
