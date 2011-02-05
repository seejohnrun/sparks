<?php

require_once dirname(__FILE__) . '/spark_utils.php';
require_once dirname(__FILE__) . '/spark_exception.php';
require_once dirname(__FILE__) . '/spark_source.php';

define('SPARK_VERSION', '0.0.1');

class SparkCLI {

    private static $commands = array(
        'install' => 'install',
        'list' => 'lister',
        'remove' => 'remove',
        'source' => 'source',
        'version' => 'version'
    );

    function __construct($spark_source) {
        $this->spark_source = $spark_source;
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

    // list the installed sparks
    private function lister() {
        foreach(scandir('./third_party') as $item) {
            if (!is_dir("./third_party/$item") || $item[0] == '.') continue;
            foreach (scandir("./third_party/$item") as $ver) {
                if (!is_dir("./third_party/$item/$ver") || $ver[0] == '.') continue;
                SparkUtils::line("$item ($ver)");
            }
        } 
    }

    private function version() {
        SparkUtils::line('version: ' . SPARK_VERSION);
    }

    private function source() {
        SparkUtils::line('source: ' . $this->spark_source->url);
    }

    private function failtown($error_message) {
        SparkUtils::line('Uh-oh!');
        SparkUtils::line($error_message);
    }

    // commands

    private function remove($args) {
        if (count($args) != 1) return $this->failtown('spark remove <name>');
        list($spark_name) = $args;

        $dir = "./third_party/$spark_name";
        SparkUtils::line("Removing $spark_name from $dir");
        if (SparkUtils::remove_full_directory("./third_party/$spark_name", true)) SparkUtils::line('Spark removed successfully!');
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
        SparkUtils::line("Retrieving spark detail from " . $this->spark_source->get_url());
        $spark = $this->spark_source->get_spark_detail($spark_name, $version);

        // retrieve the spark
        SparkUtils::line("From Downtown! Retrieving spark from " . $spark->location_detail());
        $spark->retrieve();

        SparkUtils::line("Installing spark");
        $spark->install();
        SparkUtils::line('Spark installed to ' . $spark->installed_path() . ' - He\'s on fire!');
    }

}
