<?php

class Spark {

    function __construct($name, $data) {
        $this->name = $name;
        $this->data = $data;
        $this->spark_id = $this->data->id;
        $this->version = $this->data->version;
        $this->base_location = $this->data->base_location;
        
        // used internally
        $this->temp_token = 'spark-' . $this->spark_id . '-' . time();
        $this->temp_path = sys_get_temp_dir() . '/' . $this->temp_token;

        // tell the user if its already installed and throw an error
        $this->installation_path = "./third_party/$this->name/$this->version";
        if (is_dir($this->installation_path)) {
            throw new SparkException("Already installed.  Try `php tools/spark remove $this->name`");
        }
    }

    final function installed_path() {
        return $this->installed_path;
    }

    function location_detail() { }
    function retrieve() { }

    function install() {
        @mkdir("./third_party/$this->name");
        $success = @rename($this->temp_path, $this->installation_path);
        if ($success) $this->installed_path = $this->installation_path;
    }

}
