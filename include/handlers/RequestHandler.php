<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 8/6/18
 * Time: 11:05 AM
 */
require_once (__ROOT__ . '/include/handlers/AbstractRequestHandler.php');

class RequestHandler extends AbstractRequestHandler {

    /**
     * @return GetController|PostController
     */
    public static function handle() {
        $method = $_SERVER['REQUEST_METHOD'];
        //$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
        //print_r($request);
        //print_r($method);
        print_r($_GET);

        switch ($method) {
            case 'GET':
                require_once (__ROOT__ . '/include/controllers/request/GetController.php');
                return new GetController($_GET);
            case 'POST':
                require_once (__ROOT__ . '/include/controllers/request/PostController.php');
                return new PostController($_POST);
            //case 'PUT':
              //  break;
            default:
                return null;
        }
    }
}