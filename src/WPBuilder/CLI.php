<?php

namespace WPBuilder;

use HaydenPierce\ClassFinder\ClassFinder;
use Exception;

final class CLI {

    /**
     * @param int $argc
     * @param array $argv
     * @throws Exception
     */
    public static function run(int $argc, array $argv) : void {
        try {
            $config = Config::get();
            Command::init();
            array_shift($argv);
            if($argc < 2) self::load_help($argv);
            $name = array_shift($argv);
            self::load_program($name, 'WPBuilder\programs', count($argv), $argv);
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
        self::load_program('help', 'WPBuilder\programs', count($argv), $argv);
    }

    /**
     * @param string $message
     * @throws Exception
     */
    private static function load_error(string $message) : void {
        self::load_program('error', 'WPBuilder\programs', 1, [$message]);
    }

    /**
     * @param string $name
     * @param string $namespace
     * @param int $argc
     * @param array $argv
     * @throws Exception
     */
    private static function load_program(string $name, string $namespace, int $argc, array $argv) : void {
        $name = strtolower($name);
        $programs = ClassFinder::getClassesInNamespace($namespace);
        $programs = array_filter($programs, fn(string $class) => in_array(Program::class, class_implements($class)));
        $programs = array_map(fn(string $class) : Program => new $class(), $programs);
        $program = array_values(
            array_filter($programs, fn(Program $program) => $program->get_identifier() === $name)
        );
        if(count($program) !== 1) {
            self::load_help($argv);
            return;
        }
        $program[0]->handle($argc, $argv);
        die();
    }

}