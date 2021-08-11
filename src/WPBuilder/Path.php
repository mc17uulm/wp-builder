<?php

namespace WPBuilder;

final class Path
{

    private string $path;

    protected function __construct(... $parts) {
        $this->path = join(DIRECTORY_SEPARATOR, $parts);
    }

    public function get_path() : string {
        return $this->path;
    }

    public static function create_path(... $parts) : Path {
        return new Path($parts);
    }

    public static function create_dir_path(string $file) : Path {
        return new Path(WP_BUILDER_CWD, $file);
    }

}