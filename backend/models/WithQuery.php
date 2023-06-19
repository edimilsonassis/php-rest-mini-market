<?php

namespace models;

use core\Query;

trait WithQuery
{
    protected static string $table_name = '';

    protected static ?Query $query = null;

    public static function query(): Query
    {
        if (!self::$query) {
            if (empty(static::$table_name)) {
                $table_name  = str_replace('models\\', '', static::class);
                $pattern     = '/(?<=[a-z])([A-Z])/';
                $replacement = '_$1';

                $snakeCase = preg_replace($pattern, $replacement, $table_name);

                static::$table_name = '"public"."' . strtolower($snakeCase) . '"';
            }

            self::$query = new Query(static::$table_name, static::class);
        }

        return self::$query;
    }
}