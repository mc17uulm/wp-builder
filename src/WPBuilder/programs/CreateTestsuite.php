<?php

namespace WPBuilder\programs;

use WPBuilder\BladeHandler;
use WPBuilder\Command;
use WPBuilder\Program;

final class CreateTestsuite implements Program
{

    public function get_name(): string
    {
        return "Create Testsuite";
    }

    public function get_identifier(): string
    {
        return "create-testsuite";
    }

    public function get_description(): string
    {
        return "builds a complete WP Unit testing environment";
    }

    public function handle(int $argc, array $argv): void
    {
        if(!file_exists(WP_BUILDER_CWD . "/.dev")) {
            mkdir(WP_BUILDER_CWD . "/.dev");
        }
        if(file_exists(WP_BUILDER_CWD . "/.dev/docker")) {
            Command::del_dir(WP_BUILDER_CWD . "/.dev/docker");
        }
        mkdir(WP_BUILDER_CWD . "/.dev/docker");
        BladeHandler::save(WP_BUILDER_CWD . "/.dev/docker/Dockerfile", 'Dockerfile');
        if(file_exists(WP_BUILDER_CWD . "/tests")) {
            if(file_exists(WP_BUILDER_CWD . "/tests/lib")) {
                Command::del_dir(WP_BUILDER_CWD . "/tests/lib");
                Command::del_dir(WP_BUILDER_CWD . "/tests/bootstrap.php");
            }
        } else {
            mkdir(WP_BUILDER_CWD . "/tests");
        }

        $mysql_password = "123";
        $mysql_root_password = "1234";
        $mysql_username = "wordpress";
        $mysql_database = "wordpress";
        $slug = "wp-reminder";
        $params = [
            'mysql_password' => $mysql_password,
            'mysql_root_password' => $mysql_root_password,
            'mysql_user' => $mysql_username,
            'mysql_database' => $mysql_database,
            'slug' => $slug,
            'dir' => preg_replace('/\\\\/','\\\\\\\\',WP_BUILDER_CWD)
        ];

        BladeHandler::save(WP_BUILDER_CWD . "/docker-compose.yml", 'docker-compose', $params);
        $install_file = WP_BUILDER_CWD . "/.dev/docker/install-wp-test-suite.sh";
        BladeHandler::save($install_file, 'install-wp-test-suite', $params);
        BladeHandler::save(WP_BUILDER_CWD . "/.phpcs.xml.dist", 'phpcs', ['slug' => $slug]);
        BladeHandler::save(WP_BUILDER_CWD . '/phpunit.xml.dist', 'phpunit', ['slug' => $slug]);
        BladeHandler::save(WP_BUILDER_CWD . '/tests/bootstrap.php', 'bootstrap', ['slug' => $slug]);


        //echo shell_exec(Command::get_executable() . " \"$install_file\" 2>&1");
        Command::execute_script($install_file);
        //Command::exec("docker-compose up -d");

    }

}
