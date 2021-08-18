<?php

namespace WPBuilder\programs;

use Opis\JsonSchema\Loaders\File;
use WPBuilder\BuilderException;
use WPBuilder\Collection;
use WPBuilder\Color;
use WPBuilder\Command;
use WPBuilder\Config;
use WPBuilder\FileHandler;
use WPBuilder\Path;
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
        $file_handler = new FileHandler();
        Command::writeline("\nBuild .zip");
        $file_handler->remove_file('.build');
        Command::writeline("next", Color::MAGENTA());
        $files = scandir(WP_BUILDER_CWD);
        $slug = $config->load("slug");
        Command::writeline("✓ Found slug");
        $build = $config->load("build");
        $includes = new Collection($build["includes"]);
        if(in_array('package.json', $files)) {
            if(!in_array('node_modules', $files)) {
                Command::writeline("… yarn install", Color::YELLOW());
                Command::exec('yarn install');
                Command::writeline("✓ Installed node_modules", Color::GREEN());
            }
            Command::writeline("… yarn build", Color::YELLOW());
            Command::exec('yarn build', false);
            Command::writeline("✓ Build yarn bundle", Color::GREEN());
        }
        $composer = in_array('composer.json', $files);

        $zip = "$slug.zip";
        $zip_path = Path::create_dir_path($zip);
        if(file_exists($zip_path->get_path())) {
            unlink($zip_path->get_path());
        }

        $build_path = Path::create_dir_path('.build');
        mkdir($build_path->get_path());

        $includes
            ->walk(function(string $file) use ($file_handler) {
                Command::writeline("… copy $file", Color::YELLOW());
                $file_handler->copy($file, '.build');
                Command::writeline("✓ Finished copy", Color::GREEN());
            });

        if($composer) {
            Command::writeline("… composer install", Color::YELLOW());
            Command::exec("composer install --no-ansi --no-dev --no-interaction --no-plugins --no-scripts --optimize-autoloader --prefer-dist --no-suggest --working-dir=.build");
            Command::writeline("✓ Installed composer dependencies", Color::GREEN());
        }
        Command::writeline("… zip files", Color::YELLOW());
        Command::exec("cd .build; zip -qqr ./../$zip *", false);
        Command::writeline("✓ Build $zip", Color::GREEN());
        Command::exec('rm -rf .build');
        Command::writeline("✓ Removed tmp build files", Color::GREEN());
        Command::write("✓ Bundled plugin successfully", Color::GREEN());
    }

    private static function parse_includes(Collection $includes) : Collection {
        return $includes->map(function($elem) {
           if(is_string($elem)) {
               $from = Path::create_dir_path($elem);
               $to = Path::create_dir_path(".build", ...explode("/", $elem));
               return [
                   'from' => $from,
                   'to' => $to,
                   'create' => null
               ];
           } elseif(
               is_array($elem) &&
               (count($elem) === 1) &&
               is_string(array_keys($elem)[0])
           ) {
               $key = array_keys($elem)[0];
               $from = Path::create_dir_path($key);
               $to = Path::create_dir_path(".build", ...explode("/", $elem[$key]));
               return [
                   'from' => $from,
                   'to' => $to,
                   'create' => $to
               ];
           } else {
               throw new BuilderException("Invalid element type");
           }
        });
    }

}