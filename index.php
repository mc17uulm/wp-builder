<?php

define('WP_BUILDER_VERSION', '0.1.0');
define('WP_BUILDER_PATH', dirname(__FILE__));
define('WP_BUILDER_DIR', __DIR__);
define('WP_BUILDER_SCHEMA_FILE', __DIR__ . '/wp-builder.schema.json');
define('WP_BUILDER_CWD', getcwd());

require_once __DIR__ . "/vendor/autoload.php";

use WPBuilder\CLI;

CLI::run($argc, $argv);