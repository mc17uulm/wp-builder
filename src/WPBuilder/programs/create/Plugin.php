<?php

namespace WPBuilder\programs\create;

use Jenssegers\Blade\Blade;
use Opis\JsonSchema\Loaders\File;
use WPBuilder\BladeHandler;
use WPBuilder\Color;
use WPBuilder\Command;
use WPBuilder\Config;
use WPBuilder\FileHandler;
use WPBuilder\Program;

final class Plugin implements Program
{

    public function get_name(): string
    {
        return "Plugin";
    }

    public function get_description(): string
    {
        return "Creates a new WordPress plugin structure";
    }

    private function create_main_file(array $args) : void {
        Command::writeline("… Create " . $args['slug'] . ".php", Color::YELLOW());
        BladeHandler::save($args['slug'] . '.php', 'plugin/main', $args);
        Command::writeline("✓ Created ". $args['slug'] . ".php", Color::GREEN());
    }

    private function create_dirs(array $args) : void {
        Command::writeline("… Create dirs", Color::YELLOW());
        $file_handler = new FileHandler();
        $file_handler->mkdirs([
            '.wordpress',
            'languages',
            'plugin',
            $file_handler->append_path('plugin', $args['namespace']),
            'src',
            'tests'
        ]);
        if($args['api']) {
            $file_handler->mkdir('schemas');
            BladeHandler::save($file_handler->append_path('schemas', 'error.schema.json'), 'plugin/errorschema', $args);
        }
    }

    private function create_config_files(array $args) : void {
        Command::writeline("… Create config files", Color::YELLOW());
        BladeHandler::save('.gitignore', 'plugin/gitignore', $args);
        Command::writeline("✓ Created .gitignore", Color::GREEN());
        BladeHandler::save('README.md', 'plugin/README', $args);
        Command::writeline("✓ Created README.md", Color::GREEN());
        BladeHandler::save('phpstan.neon', 'plugin/phpstan', $args);
        Command::writeline("✓ Created phpstan.neon", Color::GREEN());
        BladeHandler::save('readme.txt', 'plugin/readmewp', $args);
        Command::writeline("✓ Created readme.txt", Color::GREEN());
        BladeHandler::save('tsconfig.json', 'plugin/tsconfig', $args);
        Command::writeline("✓ Created tsconfig.json", Color::GREEN());
        BladeHandler::save('webpack.config.js', 'plugin/webpack', $args);
        Command::writeline("✓ Created webpack.config.js", Color::GREEN());
        BladeHandler::save('wp-builder.json', 'plugin/wp-builder', $args);
        Command::writeline("✓ Created wp-builder.json", Color::GREEN());
        Command::writeline("✓ Created config files", Color::GREEN());
    }

    private function create_php_files(array $args) : void {
        $file_handler = new FileHandler();
        Command::writeline("… Create php files", Color::YELLOW());
        BladeHandler::save($file_handler->append_path('plugin', $args['namespace'], 'Loader.php'), 'plugin/Loader', $args);
        Command::writeline("✓ Created Loader.php", Color::GREEN());
        BladeHandler::save($file_handler->append_path('plugin', $args['namespace'], 'Log.php'), 'plugin/Log', $args);
        Command::writeline("✓ Created Log.php", Color::GREEN());
        BladeHandler::save($file_handler->append_path('plugin', $args['namespace'], 'PluginException.php'), 'plugin/PluginException', $args);
        Command::writeline("✓ Created PluginException.php", Color::GREEN());
    }

    private function load_composer(array $args) : void {
        Command::writeline("… Install and load composer", Color::YELLOW());
        BladeHandler::save('composer.json', 'plugin/composer', $args);
        Command::writeline("✓ Created composer.json", Color::GREEN());
        Command::writeline("… Install dependencies", Color::YELLOW());
        Command::exec('composer update', false);
        Command::writeline("✓ Loaded composer", Color::GREEN());
    }

    private function load_yarn(array $args) : void {
        Command::writeline("… Install and load yarn", Color::YELLOW());
        BladeHandler::save('package.json', 'plugin/package', $args);
        Command::writeline("✓ Created package.json", Color::GREEN());
        Command::writeline("… Install dev dependencies", Color::YELLOW());
        Command::exec('yarn add --dev @babel/cli @babel/core @babel/preset-react @babel/preset-typescript @wordpress/dependency-extraction-webpack-plugin @wordpress/env @wordpress/scripts babel-loader css-loader esbuild-loader eslint eslint-plugin-react file-loader mini-css-extract-plugin node-sass sass-loader style-loader svg-url-loader ts-loader typescript url-loader webpack webpack-cli', false);
        Command::writeline("… Install dependencies", Color::YELLOW());
        Command::exec('yarn add @types/react @types/react-dom @wordpress/i18n ajv react react-dom semantic-ui-css semantic-ui-react', false);
        Command::writeline("✓ Loaded yarn", Color::GREEN());
    }

    /**
     * @return array
     */
    private function get_arguments() : array {
        $out = [];
        $out['plugin_name'] = Command::ask('Please insert your plugin name: ');
        $slug = strtolower(str_replace(' ', '-', $out['plugin_name']));
        if(!Command::ask_yn_question('Your slug will be "' . $slug . '". Ok? [y/n]: ')) {
            $slug = Command::ask('Please insert your own slug: ');
        }
        $out['slug'] = $slug;
        $namespace = str_replace(' ', '', ucwords($out['plugin_name']));
        if(!Command::ask_yn_question('Your namespace identifier will be "' . $namespace . '". Ok? [y/n]: ')) {
            $namespace = Command::ask('Please insert your own namespace: ');
        }
        $out['namespace'] = $namespace;
        $out['camel_case'] = str_replace(' ', '_', strtoupper($out['plugin_name']));
        $out['description'] = Command::ask('Please give a short description of your plugin: ');
        $out['author_name'] = Command::ask_default('Please insert your author name [default="CodeLeaf"]: ', 'CodeLeaf');
        $out['author_slug'] = Command::ask_default('Please insert your author slug [default="code-leaf"]: ', 'code-leaf');
        $out['author_email'] = Command::ask_default('Please insert your author email [default="development@code-leaf.de"]: ', 'development@code-leaf.de');
        $out['author_uri'] = Command::ask_default('Please insert your author url [default="https://code-leaf.de"]: ', 'https://code-leaf.de');
        $version = Command::ask_default('Please give the required php version [default="7.4"|8.0|8.1]: ', '7.4');
        if(!in_array($version, ['7.4', '8.0', '8.1'])) {
            Command::writeline("Unknown version '$version'. Used default version '7.4'", Color::RED());
            $version = '7.4';
        }
        $out['php_version'] = $version;
        $out['api'] = Command::ask_yn_question('Do you want to include yarn [React, ReactDOM, etc.] for frontend handling? [y/n]: ');

        return $out;
    }

    /**
     * @param array $args
     * @return bool
     */
    private function check_arguments(array $args) : bool {
        Command::writeline(json_encode($args, JSON_PRETTY_PRINT), Color::LIGHT_BLUE());
        return Command::ask_yn_question('Do you really want to create a plugin dir with the above given information? [y/n]: ');
    }

    public function handle(int $argc, array $argv): void
    {
        Command::writeline("\nCreate Plugin", Color::BLUE());
        // Check if wp-build create plugin '$path' is the given command
        if(count($argv) !== 1) {
            Command::write('No valid path given. Abort!', Color::RED());
            return;
        }
        $path = $argv[0];
        // Check if $path already exists
        if(file_exists($path)) {
            Command::write("Path '$path' already exists. Abort!", Color::RED());
            return;
        }
        Command::writeline("Building plugin in '$path'", Color::BLUE());
        $args = $this->get_arguments();
        if(!$this->check_arguments($args)) {
            Command::write('Abort!', Color::RED());
            return;
        }
        // Create plugin dir and check if successful
        Command::exec("mkdir $path");
        if(!file_exists($path) || !is_dir($path)) {
            Command::write('Could not build path. Abort!', Color::RED());
            return;
        }

        // change cwd to $path
        chdir($path);

        $this->create_dirs($args);
        $this->create_main_file($args);
        $this->create_config_files($args);
        $this->create_php_files($args);
        $this->load_composer($args);
        if($args['api']) {
            $this->load_yarn($args);
        }

        Command::exec('git init');
        Command::write("\n✓ Created plugin '{$args['namespace']}' in '$path", Color::GREEN());
    }

}