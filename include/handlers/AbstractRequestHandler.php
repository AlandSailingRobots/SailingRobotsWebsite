<?php
/**
 * Created by PhpStorm.
 * User: sailbot
 * Date: 8/6/18
 * Time: 11:10 AM
 */

require_once 'RequestHandlerInterface.php';
require_once (__ROOT__ . '/include/controllers/request/GetController.php');
require_once (__ROOT__ . '/include/controllers/request/PostController.php');

abstract class AbstractRequestHandler implements RequestHandlerInterface {
    /**
     * @return controller
     */
    public static function handle () {
        $controller = null;
        return $controller;
    }
}