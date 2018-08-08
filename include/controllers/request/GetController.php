<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 8/1/18
 * Time: 4:42 PM
 */
include(__ROOT__ . '/include/controllers/logs/DataLogController.php');
include(__ROOT__ . '/include/controllers/logs/LiveLogController.php');

class GetController {
    private $request;

    public function __construct ($request) {
        $this->request = $request;
    }

    /**
     * @return LogControllerInterface
     */
    public function retrieveController () : LogControllerInterface {
        return self::checkRoute();
    }

    public function checkRoute () : LogControllerInterface {
        $route = explode("/", substr(@$_SERVER['PHP_SELF'], 1));

        # @DEBUG
        //echo $_SERVER['PHP_SELF'];
        echo '<pre>';
        print_r($route);
        echo '</pre>';
        # @DEBUG

        // logs/saved_logs/
        if ( in_array('logs', $route) AND in_array('saved_logs', $route) ) {
            return new DataLogController ($this->request);
        }

        // logs/live
        if ( in_array('logs', $route) AND in_array('live', $route) ) {
            return new LiveLogController ($this->request);
        }
    }

}