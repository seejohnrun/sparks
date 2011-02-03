<?php
require_once dirname(__FILE__) . '/spark_source.php';

class SparkCLI {

    private static $disallowed_commands = array('execute', 'failtown', 'line');

    function __construct($spark_source) {
        $this->spark_source = $spark_source;
    }

    function execute($command, $args = array()) {
        if (in_array($command, self::$disallowed_commands) || !method_exists($this, $command)) {
            $this->failtown("Unknown action: $command");
            return;
        }
        $this->$command($args);
    }

    private function source() {
        $this->line('source: ' . $this->spark_source->url);
    }

    private function failtown($error_message) {
        $this->line('Uh-oh!');
        $this->line($error_message);
    }

    // commands

    private function install($args) {
        if (count($args) != 1) return $this->failtown('spark install <name>');
        list($spark_name) = $args;

        // retrieve the spark details
        $this->line("Retrieving spark detail from " . $this->spark_source->get_url());
        $spark = $this->spark_source->get_spark_detail($spark_name);

        // retrieve the spark
        $this->line("Retrieving spark from " . $spark->location_detail());
        $spark->retrieve();

        $this->line("Installing spark");
        $spark->install();
        $this->line('Spark installed to ' . $spark->installed_path() . ' - You\'re on fire!');
    }

    private static function line($msg) {
        echo "[SPARK] $msg\n";
    }

}
