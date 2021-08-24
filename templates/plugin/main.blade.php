<{{ "?" }}php
/**
 * {{ $namespace }}
 *
 * @package     {{ $namespace }}
 * @author      {{ $author_name }}
 * @copyright   @year() {{ $author_name }}
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: {{ $namespace }}
 * Description: {{ $description }}
 * Author: {{ $author_name }}
 * Author URI: {{ $author_uri }}
 * Version: 0.1.0
 * Text Domain: {{ $slug }}
 * Domain Path: /languages/
 * License: GNU General Public License v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Tags:
 * Requires PHP: {{ $php_version }}
 *
 * === Plugin Information ===
 *
 * Version 0.1.0
 * Date: @date()
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

declare(strict_types=1);

if(!defined('ABSPATH')) die('Invalid Request');
if(!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 70400) {
    error_log('{{ $namespace }} | ERROR | plugin requires php version >= 7.4. Given (' . PHP_VERSION . ')');
    die('Plugin requires php version >= 7.4');
}

define("{{$camel_case}}_VERSION", "0.1.0");
define("{{$camel_case}}_SLUG", "{{ $slug }}");
define("{{$camel_case}}_TEXTDOMAIN", "{{ $slug }}");
define("{{$camel_case}}_FILE", __FILE__);
define("{{$camel_case}}_URL", plugin_dir_url(__FILE__));
define("{{$camel_case}}_PATH", plugin_dir_path(__FILE__));
define("{{$camel_case}}_BASENAME", plugin_basename(__FILE__));
define("{{$camel_case}}_DIR", __DIR__);
define("{{$camel_case}}_DEBUG", true);
@if ($api)
define("{{$camel_case}}_SCHEMAS", __DIR__ . "/schemas/");
@endif

require_once __DIR__ . "/vendor/autoload.php";

use {{ $namespace }}\Loader;
use {{ $namespace }}\Log;
use {{ $namespace }}\PluginException;

$log = Log::get();
$loader = new Loader(__FILE__);

try {
 $loader->run();
} catch(PluginException $e) {
 $log->error($e->getMessage());
 if({{ $camel_case }}_DEBUG) {
  $log->error($e->get_debug_msg());
 }
} catch(Exception $e) {
 $log->error($e->getMessage());
}
