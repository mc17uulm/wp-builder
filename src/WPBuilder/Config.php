<?php

namespace WPBuilder;

use JsonException;

/**
 *
 */
final class Config
{

    /**
     * @var Config|null
     */
    private static ?Config $instance = null;

    public array $config;

    /**
     * @throws BuilderException
     */
    protected function __construct() {
        $config_file = WP_BUILDER_CWD . "\wp-builder.json";
        if(!file_exists($config_file)) throw new BuilderException('needing wp-builder.json file');
        if(!is_readable($config_file)) throw new BuilderException('config file not readable');
        if(!is_writable($config_file)) throw new BuilderException('config file is not writeable');

        $content = file_get_contents($config_file);
        try {
            $this->config = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new BuilderException($e->getMessage());
        }
    }

    /**
     * @param string $key
     * @return mixed
     * @throws BuilderException
     */
    public function load(string $key) {
        if(!isset($this->config[$key])) throw new BuilderException("No key '$key' in config file");
        return $this->config[$key];
    }

    /**
     * @return Config
     */
    public static function get() : Config {
        if(self::$instance === null) {
            self::$instance = new Config();
        }
        return self::$instance;
    }

}