<?php

namespace WPBuilder\programs;

use WPBuilder\Command;
use WPBuilder\programs\create\Plugin;
use WPBuilder\Program;

final class Create implements Program
{

    public function get_name(): string
    {
        return "Create";
    }

    public function get_description(): string
    {
        return "Creates plugins/templates etc.";
    }

    public function handle(int $argc, array $argv): void
    {
        $program = array_shift($argv);
        switch(strtolower($program)) {
            case 'plugin': (new Plugin())->handle(count($argv), $argv);
                break;
            default: Error::error("Unknown program '$program'");
        }
    }

}