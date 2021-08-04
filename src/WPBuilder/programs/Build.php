<?php

namespace WPBuilder\programs;

use WPBuilder\Command;
use WPBuilder\Config;
use WPBuilder\Program;

final class Build implements Program
{

    public function get_name(): string
    {
        return 'Build';
    }

    public function get_description(): string
    {
        return 'Builds package based on current wp-builder.json';
    }

    public function get_identifier(): string
    {
        return 'build';
    }

    /**
     * @param int $argc
     * @param array $argv
     * @throws \WPBuilder\BuilderException
     */
    public function handle(int $argc = 0, array $argv = []): void
    {
        $config = Config::get();
        $files = scandir(WP_BUILDER_CWD);
        $slug = $config->load("slug");
        $build = $config->load("build");
        $includes = $build["includes"];
        $yarn = $composer = false;
        if(in_array('yarn.lock', $files)) {
            $yarn = true;
        }
        if(in_array('composer.json', $files)) {
            $composer = true;
        }

        $zip = "$slug.zip";
        //unlink(WP_BUILDER_CWD . "/$zip");
        if($yarn) {
        //    Command::exec('yarn build', WP_BUILDER_CWD);
        }
        //mkdir(WP_BUILDER_CWD . '/.build');
        //mkdir(WP_BUILDER_CWD . './build/dist');
        foreach($includes as $include) {
            $path = $include;
            if(($pos = strpos($include, "*")) !== false) {
                $path = substr($include, 0, $pos - 1);
            }
            $path = WP_BUILDER_CWD . '/.build/$path';

        }
    }

}