<?php

class Install_Test extends Spark_Test_Case {

    function test_install_with_version() {
        ob_start();

        $clines = $this->capture_buffer_lines(function($cli) {
            $cli->execute('install', array('-v1.0', 'example-spark'));
        });
        $success = (bool) (strpos(end($clines), '[ SPARK ]  Spark installed') === 0);
        SparkUtils::remove_full_directory(SPARK_PATH . '/example-spark');
        $this->assertEquals(true, $success);

        ob_end_clean();
    }

    function test_install_without_version() {
        ob_start();

        $clines = $this->capture_buffer_lines(function($cli) {
            $cli->execute('install', array('example-spark'));
        });
        $success = (bool) (strpos(end($clines), '[ SPARK ]  Spark installed') === 0);
        SparkUtils::remove_full_directory(SPARK_PATH . '/example-spark');
        $this->assertEquals(true, $success);

        ob_end_clean();
    }

    function test_install_with_invalid_spark() {
        ob_start();

        $clines = $this->capture_buffer_lines(function($cli) {
            $cli->execute('install', array('jjks7878erHjhsjdkksj'));
        });
        $success = (bool) (strpos(end($clines), '[ ERROR ]  Unable to find spark') === 0);
        SparkUtils::remove_full_directory(SPARK_PATH . '/example-spark');
        $this->assertEquals(true, $success);

        ob_end_clean();
    }

    function test_install_with_invalid_spark_version() {
        ob_start();

        $clines = $this->capture_buffer_lines(function($cli) {
            $cli->execute('install', array('v9.4', 'example-spark'));
        });
        $success = (bool) (strpos(reset($clines), '[ ERROR ]  Uh-oh!') === 0);
        SparkUtils::remove_full_directory(SPARK_PATH . '/example-spark');
        $this->assertEquals(true, $success);

        ob_end_clean();
    }

}
