<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 8/8/18
 * Time: 9:00 AM
 */
require_once ('AbstractLogController.php');

class DataLogController extends AbstractLogController {
    private $request;

    public function __construct($request = null) {
        $this->request = $request;
    }

    public function run () : void {
        echo '<pre>';
        print_r( self::getRequest () );
        echo '</pre>';
    }

    public function getData() {
        $request = self::getRequest();

        if ($request['boat'] == aspire) {
            // TODO
        }

    }

    /**
     * @return array|null
     */
    public function getRequest () : ?array {
        return $this->request;
    }
}