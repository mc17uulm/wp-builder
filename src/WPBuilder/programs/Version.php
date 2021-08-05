<?php

namespace WPBuilder\programs;

use WPBuilder\Color;
use WPBuilder\Command;
use WPBuilder\Program;

final class Version implements Program
{

    public function get_identifier(): string
    {
        return 'version';
    }

    public function get_name(): string
    {
        return 'Version';
    }

    public function get_description(): string
    {
        return 'Shows the current version of WPBuilder';
    }

    public function handle(int $argc = 0, array $argv = []): void
    {
        Command::writeline("WPBuilder " . WP_BUILDER_VERSION . " (cli)", Color::GREEN());
        Command::writeline("Copyright (c) CodeLeaf");
    }

    public static function print_header() : void {
        (new Version())->handle();
    }

}