<?php

require 'lib/spark/spark_cli.php';

class Version_test extends PHPUnit_Framework_TestCase {

    function setUp() {
        $this->source_names[] = 'getsparks.org';
        $this->sources = array_map(function($n) { return new SparkSource($n); }, $this->source_names);
        $this->cli = new SparkCLI($this->sources);
    }

    private function capture_buffer_lines($func) {
        ob_start();
        $func($this->cli); 
        $t = ob_get_contents();
        ob_end_clean();
        if ($t == '') return array(); // empty
        return explode("\n", substr($t, 0, count($t) - 2));
    }

    function test_version() {
        $clines = $this->capture_buffer_lines(function($cli) {
            $cli->execute('version'); 
        });
        $this->assertEquals(array(SPARK_VERSION), $clines);
    }

    function test_sources() {
        $clines = $this->capture_buffer_lines(function($cli) {
            $cli->execute('sources');
        });
        $this->assertEquals($this->source_names, $clines);
    }

    function test_bad_command() {
        $clines = $this->capture_buffer_lines(function($cli) {
            $cli->execute('fake');
        });
        $this->assertEquals(array('[ ERROR ]  Uh-oh!', '[ ERROR ]  Unknown action: fake'), $clines);
    }

    function test_search_empty() {
        $clines = $this->capture_buffer_lines(function($cli) {
            $cli->execute('search', array('nothing_found_here'));
        });
        $this->assertEquals(array(), $clines);
    }

}
