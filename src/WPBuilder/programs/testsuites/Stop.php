<?php

namespace WPBuilder\programs\testsuites;

use WPBuilder\Color;
use WPBuilder\Command;
use WPBuilder\Program;

final class Stop implements Program
{

    /**
     * @return string
     */
    public function get_name(): string
    {
        return 'Stop';
    }

    /**
     * @return string
     */
    public function get_description(): string
    {
        return 'Stops the docker containers of the testing environment';
    }

    /**
     * @return string
     */
    public function get_identifier(): string
    {
        return 'stop';
    }

    public function handle(int $argc, array $argv): void
    {
        Command::writeline("\nStopping Testsuite", Color::BLUE());
        if(!file_exists(WP_BUILDER_CWD . "/docker-compose.yml") || !file_exists(WP_BUILDER_CWD . "/.dev/docker/Dockerfile")) {
            Command::write("\nIMPORTANT: Testsuite not initialized. Please run first ", Color::YELLOW());
            Command::write("'wp-builder testsuite create'");
            Command::writeline(" and then ", Color::YELLOW());
            Command::write("'wp-builder testsuite start'");
            Command::write('Abort', Color::RED());
            return;
        }
        Command::writeline("… Stopping docker containers", Color::YELLOW());
        Command::exec('docker-compose down', false);
        Command::write("✓ Testsuite stopped", Color::GREEN());
    }

}