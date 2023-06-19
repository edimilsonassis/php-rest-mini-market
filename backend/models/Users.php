<?php

namespace models;

class Users extends Model
{
    use WithQuery;

    public ?int $usr_id = null;

    public ?string $urs_username = null;

    public ?string $urs_password = null;

    public ?string $urs_name = null;

    /**
     * Get a product
     * @access public
     * @return self
     */
    public static function getByUsername(string $username)
    {
        $data = [
            'urs_username' => $username
        ];

        $result = self::query()
            ->where('"urs_username" = :urs_username')
            ->bind($data)
            ->limit(1)
            ->select()
            ->fetch(\PDO::FETCH_CLASS);

        return $result;
    }

    /**
     * Get a product
     * @access public
     * @return self
     */
    public static function getById(int $id)
    {
        $data = [
            'usr_id' => $id
        ];

        $result = self::query()
            ->where('"usr_id" = :usr_id')
            ->bind($data)
            ->limit(1)
            ->select()
            ->fetch(\PDO::FETCH_CLASS);

        return $result;
    }

    /**
     * Get an array with the users list
     * @access public
     * @return array
     */
    public static function list()
    {
        $result = self::query()
            ->select()
            ->fetchAll(\PDO::FETCH_CLASS, self::class);

        return $result;
    }

    /**
     * Register a new user
     * @access public
     * @return bool|string
     */
    public function store()
    {
        $data = [
            'urs_name'     => $this->urs_name,
            'urs_username' => $this->urs_username,
            'urs_password' => $this->urs_password,
        ];

        $result = self::query()
            ->bind($data)
            ->insert();

        return $result;
    }

    /**
     * Update a user by ID
     * @access public
     * @return bool
     */
    public function update()
    {
        $data = [
            'urs_name'     => $this->urs_name,
            'urs_username' => $this->urs_username,
            'urs_password' => $this->urs_password,
        ];

        return self::query()
            ->where('"usr_id" = :usr_id')
            ->bind($data)
            ->update()
            ->rowCount();
    }
}