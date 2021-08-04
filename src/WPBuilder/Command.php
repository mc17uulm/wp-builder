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
        // Turn off PHP output compression
        ini_set('zlib.output_compression', false);
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

    public static function exec(array $cmd) : void {
        flush();
        $process = proc_open($cmd, self::DESCRIPTOR_SPEC, $pipes, realpath(getcwd()), [], ['bypass_shell' => true]);
        if(is_resource($process)) {
            while($s = fgets($pipes[1])) {
                print $s;
                flush();
            }
        } else {
            echo "no resource\n";
        }
    }

    public static function del_dir(string $dirname) : void {
        self::exec([
            'rm',
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

}