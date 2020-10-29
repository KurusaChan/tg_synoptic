<?php

namespace App\Utils;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Twig
{

    /**
     * @var FilesystemLoader
     */
    private static $loader;

    /**
     * @var Environment
     */
    private static $environment;

    public static function getInstance()
    {
        if (is_null(self::$environment)) {
            self::$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../templates');
            self::$environment = new \Twig\Environment(self::$loader, [
                'debug' => true
            ]);
        }

        self::$environment->addExtension(new \Twig\Extension\DebugExtension());

        return self::$environment;
    }

}