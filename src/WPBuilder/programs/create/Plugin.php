<?php

namespace WPBuilder\programs\create;

use WPBuilder\BladeHandler;
use WPBuilder\Command;
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
        $out['plugin_description'] = Command::ask('Please give a short description of your plugin: ');
        $out['author_name'] = Command::ask('Please insert your author name: ');
        $out['author_email'] = Command::ask('Please insert your author email: ');
        BladeHandler::save($out['slug'] . '.php', 'plugin/main', $out);
        return $out;
    }

    public function handle(int $argc, array $argv): void
    {
        $this->get_arguments();
    }

}