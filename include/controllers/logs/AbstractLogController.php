<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 8/8/18
 * Time: 9:17 AM
 */
require_once ('LogControllerInterface.php');

abstract class AbstractLogController implements LogControllerInterface {
    public function run () : void {}
}