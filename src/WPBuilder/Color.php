<?php

namespace WPBuilder;

use MyCLabs\Enum\Enum;

/**
 * @method static Color DEFAULT()
 * @method static Color BLACK()
 * @method static Color RED()
 * @method static Color GREEN()
 * @method static Color YELLOW()
 * @method static Color BLUE()
 * @method static Color MAGENTA()
 * @method static Color CYAN()
 * @method static Color LIGHT_GREY()
 * @method static Color DARK_GREY()
 * @method static Color LIGHT_RED()
 * @method static Color LIGHT_GREEN()
 * @method static Color LIGHT_YELLOW()
 * @method static Color LIGHT_BLUE()
 * @method static Color LIGHT_MAGENTA()
 * @method static Color LIGHT_CYAN()
 * @method static Color WHITE()
 */
final class Color extends Enum
{

    private const DEFAULT = "\033[0;39m";
    private const BLACK = "\033[0;30m";
    private const RED = "\033[0;31m";
    private const GREEN = "\033[0;32m";
    private const YELLOW = "\033[0;33m";
    private const BLUE = "\033[0;34m";
    private const MAGENTA = "\033[35m";
    private const CYAN = "\033[36m";
    private const LIGHT_GREY = "\033[37m";
    private const DARK_GREY = "\033[90m";
    private const LIGHT_RED = "\033[91m";
    private const LIGHT_GREEN = "\033[92m";
    private const LIGHT_YELLOW = "\033[93m";
    private const LIGHT_BLUE = "\033[94m";
    private const LIGHT_MAGENTA = "\033[95m";
    private const LIGHT_CYAN = "\033[96m";
    private const WHITE = "\033[97m";
}