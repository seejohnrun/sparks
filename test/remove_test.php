<?php

class Remove_Test extends Spark_Test_Case {

    function test_remove_with_version() {
        ob_start();
        // Test install with a version specified
        $this->cli->execute('install', array('-v1.0', 'example-spark')); // Spark needs installed first
        $clines = $this->capture_buffer_lines(function($cli) {
            $cli->execute('remove', array('-v1.0', 'example-spark'));
        });
        $success = (bool) (strpos(end($clines), '[ SPARK ]  Spark removed') === 0 && ! is_dir(SPARK_PATH.'/example-spark'));
        $this->assertEquals(true, $success);
        SparkUtils::remove_full_directory(SPARK_PATH . '/example-spark');

        ob_end_clean();
    }

    function test_remove_without_flags() {
        ob_start();

        $this->cli->execute('install', array('-v1.0', 'example-spark')); // Spark needs installed first
        $clines = $this->capture_buffer_lines(function($cli) {
            $cli->execute('remove', array('example-spark'));
        });
        $success = (bool) (strpos(end($clines), '[ ERROR ]  Please specify') === 0 && is_dir(SPARK_PATH.'/example-spark'));
        $this->assertEquals(true, $success);
        SparkUtils::remove_full_directory(SPARK_PATH . '/example-spark');

        ob_end_clean();
    }

    function test_remove_with_f_flag() {
        ob_start();

        $this->cli->execute('install', array('-v1.0', 'example-spark')); // Spark needs installed first
        $clines = $this->capture_buffer_lines(function($cli) {
            $cli->execute('remove', array('-f', 'example-spark'));
        });
        $success = (bool) (strpos(end($clines), '[ SPARK ]  Spark removed') === 0 && ! is_dir(SPARK_PATH.'/example-spark'));
        $this->assertEquals(true, $success);
        SparkUtils::remove_full_directory(SPARK_PATH . '/example-spark');

        ob_end_clean();
    }

    function test_remove_with_invalid_version() {
        ob_start();

        $this->cli->execute('install', array('-v1.0', 'example-spark')); // Spark needs installed first
        $clines = $this->capture_buffer_lines(function($cli) {
            $cli->execute('remove', array('-v9.4', 'example-spark'));
        });
        $success = (bool) (strpos(end($clines), '[ SPARK ]  Looks like that spark isn\'t installed') === 0 && is_dir(SPARK_PATH.'/example-spark'));
        $this->assertEquals(true, $success);
        SparkUtils::remove_full_directory(SPARK_PATH . '/example-spark');

        ob_end_clean();
    }


}
