<?php

namespace WPBuilder;

use Exception;
use WPBuilder\programs\Version;

final class CLI {


    /**
     * @param int $argc
     * @param array $argv
     * @throws Exception
     */
    public static function run(int $argc, array $argv) : void {
        Version::print_header();
        try {
            Config::get();
            array_shift($argv);
            if($argc < 2) self::load_help($argv);
            $name = array_shift($argv);
            self::load_program($name, count($argv), $argv);
        } catch(BuilderException $e) {
            self::load_error($e->get_debug_msg());
        }
        die();
    }

    /**
     * @param array $argv
     * @throws Exception
     */
    private static function load_help(array $argv) : void {
        self::load_program('help', count($argv), $argv);
    }

    /**
     * @param string $message
     * @throws Exception
     */
    private static function load_error(string $message) : void {
        self::load_program('error',1, [$message]);
    }

    /**
     * @param string $name
     * @param int $argc
     * @param array $argv
     * @throws Exception
     */
    private static function load_program(string $name, int $argc, array $argv) : void {
        $name = ucfirst($name);
        $namespace = 'WPBuilder\programs';
        $class = "$namespace\\$name";
        if(
            class_exists($class) &&
            in_array(Program::class, class_implements($class))
        ) {
            $program = new $class();
            assert($program instanceof Program);
            $program->handle($argc, $argv);
            die();
        }
        self::load_help($argv);
    }

    /**
     * @param array $argv
     * @return array
     */
    public static function parse_arguments(array $argv) : array {
        $out = [];
        foreach($argv as $arg) {
            if(substr($arg, 0, 2) === "--") {
                $arg = substr($arg, 2);
            } else {
                array_push($out, $arg);
                break;
            }
            $parts = explode("=", $arg);
            if(count($parts) === 2) {
                $out[$parts[0]] = $parts[1];
            }
        }
        return $out;
    }

}