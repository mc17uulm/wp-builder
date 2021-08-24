<{{ "?php" }}

namespace {{ $namespace }};

/**
* Class Log
* @package {{ $namespace }}
*/
final class Log
{
    /**
    * @var Log|null
    */
    private static ?Log $instance = null;

    /**
    * @return Log
    */
    public static function get() : Log {
        if(self::$instance === null) {
            self::$instance = new Log();
        }
        return self::$instance;
    }

    /**
    * @param string $message
    */
    public function info(string $message) : void {
        $this->print_log("INFO", $message);
    }

    /**
    * @param string $message
    */
    public function warning(string $message) : void {
        $this->print_log("WARNING", $message);
    }

    /**
    * @param string $message
    */
    public function error(string $message) : void {
        $this->print_log("ERROR", $message);
    }

    /**
    * @param string $type
    * @param string $message
    */
    private function print_log(string $type, string $message) : void
    {
        $date = date('c');
        if (!defined('{{ $camel_case }}_VERSION')) die('Invalid access');
        $version = {{ $camel_case }}_VERSION;
        $backtrace = debug_backtrace()[0];
        $file = $backtrace['file'];
        $line = $backtrace['line'];
        $line = "$date | $version | $file | $line | $type | $message";
        error_log($line);
    }

}