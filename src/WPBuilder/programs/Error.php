<?php

namespace WPBuilder\programs;

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
        (new Version())->handle();
        echo "\n";
        echo "ERROR: $message\n";
    }

}