<?php

namespace core;

use Exception;

class WithViews
{
    private static $twig;

    /**
     * Get the instance of twig
     * @param string $name
     * @param \Twig\Environment
     */
    public static function getInstance(): \Twig\Environment
    {
        if (!isset(self::$twig)) {
            $loader = new \Twig\Loader\FilesystemLoader('views', __env);

            self::$twig = new \Twig\Environment($loader);
        }

        return self::$twig;
    }

    /**
     * Render view
     * @param string $name
     * @param array $data 
     */
    public static function view(string $name, array $data = [])
    {
        $appName = $_ENV['APP_NAME'];

        $twig = self::getInstance();
        try {
            if (!file_exists(__env . "/views/$name.html.twig"))
                throw new Exception("View $name not found");

            return $twig->render(
                "$name.html.twig",
                array_merge($data, [
                    'appName' => $appName,
                ])
            );
        } catch (\Exception $e) {
            return $twig->render("errors/404.html.twig", [
                'message' => $e->getMessage()
            ]);
        }
    }

}