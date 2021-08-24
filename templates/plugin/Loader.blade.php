<{{ "?php" }}

namespace {{ $namespace }};


/**
* Class Loader
* @package {{ $namespace }}
*/
final class Loader {

    /**
    * @var string
    */
    private string $file;
    /**
    * @var string|false
    */
    private $version;
    /**
    * @var array
    */
    private array $dependencies;

    /**
    * @param string $file
    */
    public function __construct(string $file) {
        $this->file = $file;
        $this->version = defined("{{ $camel_case }}_VERSION") ? {{ $camel_case }}_VERSION : false;
        $this->dependencies = [];
    }

    /**
    * @throws PluginException
    */
    public function run() : void {

        load_plugin_textdomain('{{ $slug }}', false, dirname(plugin_basename($this->file)) . "/languages/");

        register_activation_hook($this->file, [$this, 'activate']);
        register_deactivation_hook($this->file, [$this, 'deactivate']);

    }

    /**
    * @throws PluginException
    */
    public function activate() : void {}

    /**
    * @throws PluginException
    */
    public function deactivate() : void {}

}

