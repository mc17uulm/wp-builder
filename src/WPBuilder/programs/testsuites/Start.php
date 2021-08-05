<?php

namespace WPBuilder\programs\testsuites;

use WPBuilder\Color;
use WPBuilder\Command;
use WPBuilder\Program;

final class Start implements Program
{

    /**
     * @return string
     */
    public function get_name(): string
    {
        return 'Start';
    }

    /**
     * @return string
     */
    public function get_description(): string
    {
        return 'Starts the docker containers for the testing environment';
    }

    /**
     * @return string
     */
    public function get_identifier(): string
    {
        return 'start';
    }

    public function handle(int $argc, array $argv): void
    {
        Command::writeline("\nStarting Testsuite", Color::BLUE());
        if(!file_exists(WP_BUILDER_CWD . "/docker-compose.yml") || !file_exists(WP_BUILDER_CWD . "/.dev/docker/Dockerfile")) {
            Command::write("\nIMPORTANT: Testsuite not initialized. Please run first ", Color::YELLOW());
            Command::write("'wp-builder testsuite create'");
            Command::writeline(" before starting", Color::YELLOW());
            Command::write('Abort', Color::RED());
            return;
        }
        Command::writeline("… Starting docker containers", Color::YELLOW());
        Command::exec('docker-compose', ['up', '-d']);
        Command::write("✓ Testsuite is now live", Color::GREEN());
    }

}