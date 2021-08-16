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
        $this->path = str_replace(['/', '\\'], '\\\\', join(DIRECTORY_SEPARATOR, $parts));
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
     * @param string $file
     * @return Path
     */
    public static function create_dir_path(string $file) : Path {
        return new Path(WP_BUILDER_CWD, $file);
    }

    public static function get_separator() : string {
        return str_replace(['/', '\\'], ['//', '\\\\'], DIRECTORY_SEPARATOR);
    }
}