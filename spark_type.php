<?php

class Spark {

    function __construct($name, $data) {
        $this->name = $name;
        $this->data = $data;
        $this->spark_id = $this->data->id;
        $this->base_location = $this->data->base_location;
        // used internally
        $this->temp_token = 'spark-' . $this->spark_id . '-' . time();
    }

    final function installed_path() {
        return $this->installed_path;
    }

    function location_detail() { }
    function retrieve() { }

    function install() {
        $this->installed_path = $this->temp_path; 
    }

}
