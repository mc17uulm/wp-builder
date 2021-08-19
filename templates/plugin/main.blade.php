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
 * Description: {{ $plugin_description }}
 * Author: {{ $author_name }}
 * Author URI: https://code-leaf.de
 * Version: 0.1.0
 * Text Domain: {{ $slug }}
 * Domain Path: /languages/
 * License: GNU General Public License v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Tags:
 * Requires PHP: 7.4
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
    error_log('{{ $namespace }} | ERROR | plugin requires php version >= 7.4. Given (' . PHP_VERSION . ')';
    die('Plugin requires php version >= 7.4');
}
