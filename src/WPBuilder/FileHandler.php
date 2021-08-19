<?php

namespace WPBuilder;

final class FileHandler
{

    /**
     * @param string $path
     * @return string
     */
    public function transform_path(string $path) : string {
        return str_replace(["/", "\\"], '/', $path);
    }

    /**
     * @param ...$parts
     * @return string
     */
    public function append_path(...$parts) : string {
        return join('/', $parts);
    }

    /**
     * @param array<string> $parts
     * @param string $base
     * @return string
     */
    public function create_dirs(array $parts, string $base = "") : string {
        return array_reduce($parts, function(string $carry, string $item) {
            $path = $this->append_path($carry, $item);
            if(!file_exists($path)) {
                Command::exec("mkdir $path");
            }
            return $path;
        }, $base);
    }

    /**
     * @param string $from
     * @param string $to
     */
    public function copy(string $from, string $to) : void {
        if(is_dir($from)) {
            $this->copy_dir($from, $to);
        } else {
            $this->copy_file($from, $to);
        }
    }

    /**
     * @param string $from
     * @param string $to
     */
    public function copy_dir(string $from, string $to) : void {
        $path = $this->create_dirs(explode('/', $from), $to);
        $from = $this->append_path($this->transform_path($from), "*");
        Command::exec("cp -r $from $path");
    }

    /**
     * @param string $collection
     * @param string $to
     */
    public function copy_collection(string $collection, string $to) : void {
        $parts = explode('/', $collection);
        array_pop($parts);
        $path = $this->create_dirs($parts, $to);
        Command::exec("cp -r $collection $path");
    }

    /**
     * @param string $file
     * @param string $to
     */
    public function copy_file(string $file, string $to) : void {
        $this->copy_collection($file, $to);
    }

    public function remove_file(string $file) : void {
        Command::exec("rm -rf $file", false);
    }

}