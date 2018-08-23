<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 7/26/18
 * Time: 1:21 PM
 */
require_once ('DatabaseConfiguration.php');
require_once ('DatabaseConnection.php');

abstract class AbstractDatabaseConnectionFactory
{
    /**
     * @param string $boatName
     * @return DatabaseConnection
     */
    abstract static function getDatabaseConnection (string $boatName): DatabaseConnection;
}