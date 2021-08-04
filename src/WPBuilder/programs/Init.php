<?php

namespace WPBuilder\programs;

use WPBuilder\Program;

final class Init implements Program
{

    public function get_name(): string
    {
        return "Init";
    }

    public function get_description(): string
    {
        return "Initialize";
    }

    public function get_identifier(): string
    {
        return "init";
    }

    public function handle(int $argc, array $argv): void
    {
        echo "running init\n";
    }

}