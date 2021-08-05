<?php

namespace WPBuilder\programs;

use WPBuilder\Color;
use WPBuilder\Command;
use WPBuilder\Program;

final class Test implements Program
{

    public function get_name(): string
    {
        return 'Test';
    }

    public function get_identifier(): string
    {
        return 'test';
    }

    public function get_description(): string
    {
        return 'For development';
    }

    public function handle(int $argc, array $argv): void
    {
        Command::writeline("Hallo", Color::MAGENTA());
    }

}