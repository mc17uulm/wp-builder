<?php

namespace WPBuilder;

final class Command
{

    private const DESCRIPTOR_SPEC = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w']
    ];

    public static function init() : void {
        ini_set('output_buffering', 'off');
        // Implicitly flush the buffer(s)
        ini_set('implicit_flush', true);
        ob_implicit_flush(true);
        // Clear, and turn off output buffering
        while (ob_get_level() > 0) {
            // Get the current level
            $level = ob_get_level();
            // End the buffering
            ob_end_clean();
            // If the current level has not changed, abort
            if (ob_get_level() == $level) break;
        }
        // Disable apache output buffering/compression
        if (function_exists('apache_setenv')) {
            apache_setenv('no-gzip', '1');
            apache_setenv('dont-vary', '1');
        }
    }

    public static function exec(string $cmd, array $args = [], bool $silent = false) : int {
        $process_cmd = '"' . $cmd . '" ' . self::parse_args(new Collection($args));
        $process = proc_open($process_cmd, self::DESCRIPTOR_SPEC, $pipes, null, null, ['bypass_shell' => true]);
        if(is_resource($process)) {
            while($s = fgets($pipes[1])) {
                if(!$silent) print $s;
            }
            if(!$silent) echo stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            return proc_close($process);
        }
        return -1;
    }

    public static function readline() : string {
        $handle = fopen("php://stdin", 'r');
        $response = fgets($handle);
        fclose($handle);
        return trim($response);
    }

    public static function writeline(string $text, Color $color = null) : void {
        self::write("$text\n", $color);
    }

    public static function write(string $text, Color $color = null) : void {
        if($color === null) $color = Color::DEFAULT();
        echo "$color$text";
    }

    private static function parse_args(Collection $args) : string {
        return $args
            ->map(function(string $arg, $key) : string {
                return is_string($key) ?
                    ' ' . escapeshellarg($key) . ' ' . escapeshellarg($arg) :
                    ' ' . escapeshellarg($arg);
            })
            ->reduce(function(string $carry, string $item) : string {
                return $carry . $item;
            }, "");
    }

    public static function del_dir(string $dirname) : void {
        self::exec('rm', [
            '-rf',
            $dirname
        ]);
    }

    public static function execute_script(string $script) : void {
        echo shell_exec(self::get_executable() . " \"$script\" 2>&1");
    }

    public static function get_os() : string {
        return strtolower(PHP_OS_FAMILY);
    }

    public static function get_executable() : string {
        switch(self::get_os()) {
            case "windows": return "bash.exe";
            default: return "bash";
        }
    }

    public static function format_file(string $filepath) : string {
        return preg_replace('/\\\\/','\\\\\\\\',realpath($filepath));
    }

}