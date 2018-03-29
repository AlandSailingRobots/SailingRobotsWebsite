<?php
Class Measurements {

  public function __construct() {

  }

  public function getLogData($table, $boatName) {
    //get logdata for some boat
  }

  public function __set($name, $value) {
    $this->$name = $value;
  }

  public function __get($name) {
    return $this->$name;
  }

  public function __toString() {
    $someHTMLString = null;
    //generate table data as html for template

    return $someHTMLString;
  }
}

 ?>
