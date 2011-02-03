<?php

class MercurialSpark extends Spark {

    function __construct($name, $data) {
        parent::__construct($name, $data);
        $this->tag = $this->data->version;
    }

    function location_detail() {
        return "Mercurial repository at $this->base_location";
    }

    function retrieve() {
        $this->temp_path = "/tmp/$this->temp_token";
        system("hg clone -r$this->tag $this->base_location $this->temp_path");
        system("rm -rf $this->temp_path/.hg");
    }

}
