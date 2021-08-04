<?php

namespace WPBuilder\programs;

use WPBuilder\Program;

final class Help implements Program
{

    public function get_identifier(): string
    {
        return 'help';
    }

    public function get_name(): string
    {
        return 'Help';
    }

    public function get_description(): string
    {
        return 'gives information about WPBuilder';
    }

    public function handle(int $argc, array $argv): void
    {
        echo "WPBuilder -- Version " . WP_BUILDER_VERSION . "\n";
    }

}