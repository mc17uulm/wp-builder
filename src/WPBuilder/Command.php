<?php

namespace WPBuilder;

final class Command
{

    private const DESCRIPTORS = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w']
    ];

    /**
     * @param string $cmd
     * @param bool $silent
     * @return string
     */
    public static function exec(string $cmd, bool $silent = true): string
    {
        $process = proc_open("sh -c '$cmd 2>&1'", self::DESCRIPTORS, $pipes);
        $return = "";
        if(is_resource($process)) {
            while($s = fgets($pipes[1])) {
                if(!$silent) {
                    print $s;
                } else {
                    $return .= $s;
                }
                flush();
            }
            fclose($pipes[0]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);
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

    /**
     * @param array $commands
     * @return array
     */
    public static function collect_data(array $commands) : array {
        $out = [];
        foreach($commands as $key => $command) {
            Command::write($command);
            $out[$key] = Command::readline();
        }
        return $out;
    }

    /**
     * @param string $question
     * @return string
     */
    public static function ask(string $question) : string {
        Command::write($question);
        return Command::readline();
    }

    /**
     * @param string $question
     * @param string $default
     * @return string
     */
    public static function ask_default(string $question, string $default) : string {
        $result = self::ask($question);
        return ($result === "") ? $default : $result;
    }

    /**
     * @param string $question
     * @return bool
     */
    public static function ask_yn_question(string $question) : bool {
        Command::write($question);
        return in_array(strtolower(Command::readline()), ['y', 'yes']);
    }

    /**
     * View any string as a hexdump.
     *
     * This is most commonly used to view binary data from streams
     * or sockets while debugging, but can be used to view any string
     * with non-viewable characters.
     *
     * @version     1.3.2
     * @author      Aidan Lister <aidan@php.net>
     * @author      Peter Waller <iridum@php.net>
     * @link        http://aidanlister.com/2004/04/viewing-binary-data-as-a-hexdump-in-php/
     * @param       string  $data        The string to be dumped
     * @param       bool    $htmloutput  Set to false for non-HTML output
     * @param       bool    $uppercase   Set to true for uppercase hex
     * @param       bool    $return      Set to true to return the dump
     */
    private static function hexdump (string $data, bool $htmloutput = true, bool $uppercase = false, bool $return = false)
    {
        // Init

        $hexi   = '';
        $ascii  = '';
        $dump   = ($htmloutput === true) ? '<pre>' : '';
        $offset = 0;
        $len    = strlen($data);

        // Upper or lower case hexadecimal

        $x = ($uppercase === false) ? 'x' : 'X';

        // Iterate string

        for ($i = $j = 0; $i < $len; $i++)
        {
            // Convert to hexidecimal

            $hexi .= sprintf("%02$x ", ord($data[$i]));

            // Replace non-viewable bytes with '.'

            if (ord($data[$i]) >= 32) {
                $ascii .= ($htmloutput === true) ?
                    htmlentities($data[$i]) :
                    $data[$i];
            } else {
                $ascii .= '.';
            }

            // Add extra column spacing

            if ($j === 7) {
                $hexi  .= ' ';
                $ascii .= ' ';
            }

            // Add row

            if (++$j === 16 || $i === $len - 1) {
                // Join the hexi / ascii output

                $dump .= sprintf("%04$x  %-49s  %s", $offset, $hexi, $ascii);

                // Reset vars

                $hexi   = $ascii = '';
                $offset += 16;
                $j      = 0;

                // Add newline

                if ($i !== $len - 1) {
                    $dump .= "\n";
                }
            }
        }

        // Finish dump

        $dump .= $htmloutput === true ?
            '</pre>' :
            '';
        $dump .= "\n";

        // Output method

        if ($return === false) {
            echo $dump;
            return "";
        } else {
            return $dump;
        }
    }

}