<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 8/6/18
 * Time: 11:05 AM
 */
require_once (__ROOT__ . '/include/handlers/AbstractRequestHandler.php');

class RequestHandler extends AbstractRequestHandler {

    public static function handle() {
        $method = $_SERVER['REQUEST_METHOD'];
        $request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));
        print_r($request);
        print_r($method);
        switch ($method) {
            case 'GET':
                return new GetController($request);
            case 'POST':
                return new PostController($request);
            case 'PUT':
                break;
            default:
                break;
        }
    }
}