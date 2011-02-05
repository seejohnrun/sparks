<?php

require_once dirname(__FILE__) . '/spark_utils.php';
require_once dirname(__FILE__) . '/spark_exception.php';
require_once dirname(__FILE__) . '/spark_source.php';

define('SPARK_VERSION', '0.0.1');
define('SPARK_PATH', './third_party');

class SparkCLI {

    private static $commands = array(
        'install' => 'install',
        'list' => 'lister',
        'remove' => 'remove',
        'sources' => 'sources',
        'version' => 'version',
        'help' => 'help',
        '' => 'index' // default action
    );

    function __construct($spark_sources) {
        $this->spark_sources = $spark_sources;
    }

    function execute($command, $args = array()) {
        if (!array_key_exists($command, self::$commands)) {
            $this->failtown("Unknown action: $command");
            return;
        }
        try {
            $method = self::$commands[$command];
            $this->$method($args);
        } catch (Exception $ex) {
            return $this->failtown($ex->getMessage());
        }
    }

    private function index($args) {
        SparkUtils::line('Spark (v' . SPARK_VERSION . ')');
        SparkUtils::line('by John Crepezzi (@seejohnrun) and Kenny Katzgrau (@_kennyk_)');
        SparkUtils::line();
        SparkUtils::line('For help: `php tools/spark help`');
    }

    // list the installed sparks
    private function lister() {
        foreach(scandir(SPARK_PATH) as $item) {
            if (!is_dir(SPARK_PATH . "/$item") || $item[0] == '.') continue;
            foreach (scandir(SPARK_PATH . "/$item") as $ver) {
                if (!is_dir(SPARK_PATH . "/$item/$ver") || $ver[0] == '.') continue;
                SparkUtils::line("$item ($ver)");
            }
        } 
    }

    private function version() {
        SparkUtils::line(SPARK_VERSION);
    }

    private function help() {
        SparkUtils::line('install   # Install a spark');
        SparkUtils::line('remove    # Remove a spark');
        SparkUtils::line('list      # List installed sparks');
        SparkUtils::line('sources   # Display the spark source URL(s)');
        SparkUtils::line('version   # Display the installed spark version');
        SparkUtils::line('help      # This message');
    }

    private function sources() {
        foreach($this->spark_sources as $source) {
            SparkUtils::line($source->get_url());
        }
    }

    private function failtown($error_message) {
        SparkUtils::line();
        SparkUtils::line('Uh-oh!', 'ERROR');
        SparkUtils::line($error_message, 'ERROR');
    }

    private function remove($args) {
        if (count($args) != 1) return $this->failtown('Which spark do you want to remove?');
        list($spark_name) = $args;

        $dir = SPARK_PATH . "/$spark_name";
        SparkUtils::line("Removing $spark_name from $dir");
        if (SparkUtils::remove_full_directory($dir, true)) SparkUtils::line('Spark removed successfully!');
        else SparkUtils::line('Looks like that spark isn\'t installed');
    }

   private function install($args) {

        $flats = array();
        $flags = array();
        foreach($args as $arg) {
            preg_match('/^(\-?[a-zA-Z])([^\s]+)$/', $arg, &$matches);
            if (count($matches) != 3) continue;
            $matches[0][0] == '-' ? $flags[$matches[1][1]] = $matches[2] : $flats[] = $matches[0];
        }

        if (count($flats) != 1) return $this->failtown('format: `spark install -v1.0.0 name`');
        $spark_name = $flats[0];
        $version = array_key_exists('v', $flags) ? $flags['v'] : 'HEAD';

        // retrieve the spark details
        foreach ($this->spark_sources as $source) {
            SparkUtils::line("Retrieving spark detail from " . $source->get_url(), 'SPARK');
            $spark = $source->get_spark_detail($spark_name, $version);
            if ($spark != null) break;
        }

        // did we find the details?
        if ($spark == null) throw new SparkException("Unable to find spark: $spark_name ($version) in any sources");

        // retrieve the spark
        SparkUtils::line("From Downtown! Retrieving spark from " . $spark->location_detail(), 'SPARK');
        if (!$spark->retrieve()) throw new SparkException('Failed to retrieve the spark ;(');

        SparkUtils::line("Installing spark", 'SPARK');
        $spark->install();
        SparkUtils::line('Spark installed to ' . $spark->installed_path() . ' - You\'re on fire!', 'SPARK');
    }

}
