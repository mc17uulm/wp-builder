<?php

namespace WPBuilder\programs;

use WPBuilder\BuilderException;
use WPBuilder\Collection;
use WPBuilder\Color;
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
     * @throws BuilderException
     */
    public function handle(int $argc = 0, array $argv = []): void
    {
        $config = Config::get();
        Command::writeline("\nBuild .zip", Color::BLUE());
        Command::exec('rm -rf .build');
        $files = scandir(WP_BUILDER_CWD);
        $slug = $config->load("slug");
        Command::writeline("✓ Found slug: '$slug'", Color::GREEN());
        $build = $config->load("build");
        $includes = new Collection($build["includes"]);
        if(in_array('package.json', $files)) {
            if(!in_array('node_modules', $files)) {
                Command::writeline("… yarn install", Color::YELLOW());
                Command::exec('yarn install');
                Command::writeline("✓ Installed node_modules", Color::GREEN());
            }
            Command::writeline("… yarn build", Color::YELLOW());
            Command::exec('yarn build');
            Command::writeline("✓ Build yarn bundle", Color::GREEN());
        }
        $composer = in_array('composer.json', $files);

        $zip = "$slug.zip";
        if(file_exists(WP_BUILDER_CWD . "/$zip")) {
            unlink(WP_BUILDER_CWD . "/$zip");
        }
        mkdir(WP_BUILDER_CWD . '/.build');

        $includes
            ->map(function(string $file) {
                $path = $file;
                if(($pos = strpos($file, "*")) !== false) {
                    $path = substr($file, 0, $pos - 1);
                }
                if(!file_exists(dirname(WP_BUILDER_CWD . "/$file"))) {
                    throw new BuilderException("Includes file(s) '" . WP_BUILDER_CWD . "/$file' do not exist");
                }
                // TODO: creates subdir for first row dir in includes
                if(is_dir(WP_BUILDER_CWD . "/$path")) {
                    // TODO: check of update with mkdir() --recursive mode
                    Command::exec('mkdir '. str_replace('/', '\\', ".build/$path"));
                }
                return [
                    "name" => $file,
                    "from" => WP_BUILDER_CWD . "/$file",
                    "to" => WP_BUILDER_CWD . "/.build/$path"
                ];
            })
            ->walk(function(array $files) {
                Command::writeline("… copy " . $files['name'], Color::YELLOW());
                Command::exec('cp -r ' . $files['from'] . " " . $files['to'], true);
                Command::writeline("✓ Finished copy", Color::GREEN());
            });
        if($composer) {
            Command::writeline("… composer install", Color::YELLOW());
            Command::exec('cd .build && composer install --no-ansi --no-dev --no-interaction --no-plugins --no-scripts --optimize-autoloader --prefer-dist --no-suggest');
            Command::writeline("✓ Installed composer dependencies", Color::GREEN());
        }
        Command::writeline("… zip files", Color::YELLOW());
        Command::exec("cd .build && zip -qqr ../$zip *");
        Command::writeline("✓ Build $zip", Color::GREEN());
        Command::del_dir(WP_BUILDER_CWD . '/.build');
        Command::writeline("✓ Removed tmp build files", Color::GREEN());
        Command::write("✓ Bundled plugin successfully", Color::GREEN());
    }

}