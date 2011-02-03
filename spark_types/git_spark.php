<?php

class GitSpark extends Spark {

    function __construct($name, $data) {
        parent::__construct($name, $data);
        $this->tag = $this->data->version;
    }

    function location_detail() {
        return "Git repository at $this->base_location";
    }

    function retrieve() {
        $this->temp_path = "/tmp/$this->temp_token";
        system("git clone $this->base_location $this->temp_path");
        system("cd $this->temp_path; git checkout $this->tag -b $this->temp_token");
        system("rm -rf $this->temp_path/.git");
    }

}
