<?php

require 'lib/spark/spark_cli.php';

class Version_test extends PHPUnit_Framework_TestCase {

    function setUp() {
        SparkUtils::buffer();
        $this->sources[] = new SparkSource('getsparks.org');
        $this->cli = new SparkCLI($this->sources);
    }

    function test_version() {
        $this->cli->execute('version'); 
        $this->assertEquals(array(SPARK_VERSION), SparkUtils::get_lines());
    }

}
