<?php

class Version_Test extends Spark_Test_Case {

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
