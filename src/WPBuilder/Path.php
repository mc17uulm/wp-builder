<?php

namespace WPBuilder;

/**
 *
 */
final class Path
{

    /**
     * @var string
     */
    private string $path;

    /**
     * @param ...$parts
     */
    protected function __construct(... $parts) {
        $path = join(DIRECTORY_SEPARATOR, $parts);
        if(PHP_OS === "WINNT") {
            $path = str_replace(['/', '\\'], '\\\\', $path);
        }
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function get_path() : string {
        return $this->path;
    }

    /**
     * @param ...$parts
     * @return Path
     */
    public static function create_path(... $parts) : Path {
        return new Path($parts);
    }

    /**
     * @param ...$parts
     * @return Path
     */
    public static function create_dir_path(... $parts) : Path {
        return new Path(WP_BUILDER_CWD, ... $parts);
    }

    public static function get_separator() : string {
        return str_replace(['/', '\\'], ['//', '\\\\'], DIRECTORY_SEPARATOR);
    }
}