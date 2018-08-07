<?php
/**
 * Created by PhpStorm.
 * User: sailbot
 * Date: 8/1/18
 * Time: 4:42 PM
 */

class GetController {
    private $request
    ;
    public function __construct ($request) {
        $this->request = $request;
    }

    public function run () {
        // TODO: Implement __toString() method.
        echo "yodigity";
    }
}