<?php

namespace WPBuilder;

final class Command
{

    /**
     * @param string $cmd
     * @param bool $silent
     * @return false|string|null
     */
    public static function exec(string $cmd, bool $silent = true)  {
        $return = shell_exec("$cmd 2>&1");
        if(!$silent) {
            echo $return;
        }
        return $return;
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

    public static function del_dir(string $dirname) : void {
        self::exec('rm -rf ' . $dirname);
    }

}