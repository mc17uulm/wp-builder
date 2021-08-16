<?php

namespace WPBuilder\programs;

use WPBuilder\BuilderException;
use WPBuilder\Program;
use WPBuilder\programs\testsuites\Create;
use WPBuilder\programs\testsuites\Run;
use WPBuilder\programs\testsuites\Start;
use WPBuilder\programs\testsuites\Stop;

/**
 *
 */
final class Testsuite implements Program
{

    /**
     * @return string
     */
    public function get_name(): string
    {
        return "Testsuite";
    }

    /**
     * @return string
     */
    public function get_identifier(): string
    {
        return "testsuite";
    }

    /**
     * @return string
     */
    public function get_description(): string
    {
        return "Collection of programs for the testsuite";
    }

    /**
     * @param int $argc
     * @param array $argv
     * @throws BuilderException
     */
    public function handle(int $argc, array $argv): void
    {
        $program = array_shift($argv);
        switch(strtolower($program)) {
            case 'create': (new Create())->handle(count($argv), $argv);
                break;
            case 'start': (new Start())->handle(count($argv), $argv);
                break;
            case 'stop': (new Stop())->handle(count($argv), $argv);
                break;
            case 'run': (new Run())->handle(count($argv), $argv);
                break;
            default: Error::error("Unknown program '$program'");
        }
    }

}
