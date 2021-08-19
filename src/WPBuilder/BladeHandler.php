<?php

namespace WPBuilder;

use Jenssegers\Blade\Blade;

final class BladeHandler
{

    private static ?Blade $instance = null;

    public static function get(): Blade {
        if(self::$instance === null) {
            self::$instance = new Blade(WP_BUILDER_DIR . '/templates', WP_BUILDER_DIR . '/cache');
            self::$instance->directive('year', function() {
                return date('Y');
            });
            self::$instance->directive('date', function() {
                return date('d.m.Y');
            });
            self::$instance->directive('uppercase', function($name) {
                return str_replace(' ', '_', strtoupper($name));
            });
        }
        return self::$instance;
    }

    public static function save(string $file, string $template, array $arguments = []) : bool {
        $blade = BladeHandler::get();
        $content = $blade->make($template, $arguments);
        return file_put_contents($file, $content);
    }

}