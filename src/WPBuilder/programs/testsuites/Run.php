<?php

namespace WPBuilder\programs\testsuites;

use WPBuilder\Color;
use WPBuilder\Command;
use WPBuilder\Program;

final class Run implements Program {

    public function get_name(): string
    {
        return 'Run';
    }

    public function get_identifier(): string
    {
        return 'run';
    }

    public function get_description(): string
    {
        return 'Run phpunit test environment in docker container';
    }

    public function handle(int $argc, array $argv): void
    {
        Command::writeline('Executing phpunit test environment in docker container', Color::MAGENTA());
        Command::exec('docker exec -it wordpress_test sh -c "cd /var/www/html/wp-content/plugins/wp-reminder && phpunit"', false);
    }

}