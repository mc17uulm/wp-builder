<?php

namespace WPBuilder\programs\testsuites;

use WPBuilder\BladeHandler;
use WPBuilder\CLI;
use WPBuilder\Color;
use WPBuilder\Command;
use WPBuilder\Config;
use WPBuilder\Path;
use WPBuilder\Program;
use WPBuilder\BuilderException;

/**
 *
 */
final class Create implements Program
{

    /**
     * @return string
     */
    public function get_name(): string
    {
        return 'Create';
    }

    /**
     * @return string
     */
    public function get_description(): string
    {
        return 'Creates a new testsuite in a project';
    }

    /**
     * @return string
     */
    public function get_identifier(): string
    {
        return 'create';
    }

    /**
     * @param array $argv
     * @return array
     */
    private function handle_arguments(array $argv) : array {
        $out = CLI::parse_arguments($argv);
        if(count($out) === 0) {
            Command::write('Please insert the database name: ');
            $out['db_name'] = Command::readline();
            Command::write('Please insert the database username: ');
            $out['db_user'] = Command::readline();
            Command::write('Please insert the database password: ');
            $out['db_pass'] = Command::readline();
            Command::write('Please insert the database root password: ');
            $out['db_root_pass'] = Command::readline();
            echo "\n";
        }
        return $out;
    }

    /**
     * @param int $argc
     * @param array $argv
     * @throws BuilderException
     */
    public function handle(int $argc, array $argv): void
    {
        $config = Config::get();
        Command::writeline("\nCreate Testsuite", Color::BLUE());
        $arguments = $this->handle_arguments($argv);
        if(file_exists(WP_BUILDER_CWD . "/tests/bootstrap.php")) {
            Command::write("Seems like you already created a test suite. Do you really want to recreate all files? [y/n]: ");
            if(!in_array(strtolower(Command::readline()), ['y', 'yes'])) {
                Command::write('Abort program', Color::RED());
                return;
            };
        }
        if(!file_exists(WP_BUILDER_CWD . "/.dev")) {
            mkdir(WP_BUILDER_CWD . "/.dev");
            Command::writeline("✓ Created ./.dev", Color::GREEN());
        }
        if(file_exists(WP_BUILDER_CWD . "/.dev/docker")) {
            Command::del_dir(WP_BUILDER_CWD . "/.dev/docker");
        }
        mkdir(WP_BUILDER_CWD . "/.dev/docker");
        Command::writeline("✓ Created ./.dev/docker", Color::GREEN());
        BladeHandler::save(WP_BUILDER_CWD . "/.dev/docker/Dockerfile", 'Dockerfile');
        Command::writeline("✓ Created Dockerfile", Color::GREEN());
        if(file_exists(WP_BUILDER_CWD . "/tests")) {
            if(file_exists(WP_BUILDER_CWD . "/tests/lib")) {
                Command::del_dir(WP_BUILDER_CWD . "/tests/lib");
                Command::del_dir(WP_BUILDER_CWD . "/tests/bootstrap.php");
            }
        } else {
            mkdir(WP_BUILDER_CWD . "/tests/lib");
            Command::writeline("✓ Created ./tests", Color::GREEN());
        }

        $slug = $config->load("slug");
        $params = [
            'mysql_password' => $arguments['db_pass'],
            'mysql_root_password' => $arguments['db_root_pass'],
            'mysql_user' => $arguments['db_user'],
            'mysql_database' => $arguments['db_name'],
            'slug' => $slug,
            'dir' => Path::create_dir_path('tests\\lib')->get_path(),
            'separator' => Path::get_separator()
        ];

        BladeHandler::save(WP_BUILDER_CWD . "/docker-compose.yml", 'docker-compose', $params);
        Command::writeline("✓ Created docker-compose.yml", Color::GREEN());
        $install_file = WP_BUILDER_CWD . "/.dev/docker/install-wp-test-suite.sh";
        BladeHandler::save($install_file, 'install-wp-test-suite', $params);
        BladeHandler::save(WP_BUILDER_CWD . "/.phpcs.xml.dist", 'phpcs', ['slug' => $slug]);
        Command::writeline("✓ Created phpcs.xml.dist", Color::GREEN());
        BladeHandler::save(WP_BUILDER_CWD . '/phpunit.xml.dist', 'phpunit', ['slug' => $slug]);
        Command::writeline("✓ Created phpunit.xml.dist", Color::GREEN());
        BladeHandler::save(WP_BUILDER_CWD . '/tests/bootstrap.php', 'bootstrap', ['slug' => $slug]);
        Command::writeline("✓ Created ./tests/bootstrap.php", Color::GREEN());
        Command::writeline("… Installing test suite", Color::YELLOW());
        Command::exec('sh ./.dev/docker/install-wp-test-suite.sh', false);
        Command::write("✓ WP test suite installed", Color::GREEN());
    }

}