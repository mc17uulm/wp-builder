<?php

namespace WPBuilder\programs;

use WPBuilder\Color;
use WPBuilder\Command;
use WPBuilder\Program;

final class Error implements Program
{

    public function get_name(): string
    {
        return 'Error';
    }

    public function get_description(): string
    {
        return 'Gives current error message';
    }

    public function get_identifier(): string
    {
        return 'error';
    }

    public function handle(int $argc, array $argv): void
    {
        $argc === 1 ?
            $this->print_error($argv[0]) :
            $this->print_error('Invalid use of error program');
    }

    private function print_error(string $message) : void {
        echo "\n";
        Command::write("ERROR: $message", Color::RED());
        die();
    }

    public static function error(string $message) : void {
        (new Error())->handle(1, [$message]);
    }

}