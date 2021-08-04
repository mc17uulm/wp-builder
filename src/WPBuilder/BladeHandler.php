<?php

namespace WPBuilder;

use Jenssegers\Blade\Blade;

final class BladeHandler
{

    private static ?Blade $instance = null;

    public static function get(): Blade {
        if(self::$instance === null) {
            self::$instance = new Blade('templates', 'cache');
        }
        return self::$instance;
    }

    public static function save(string $file, string $template, array $arguments = []) : bool {
        $blade = BladeHandler::get();
        $content = $blade->make($template, $arguments);
        return file_put_contents($file, $content);
    }

}