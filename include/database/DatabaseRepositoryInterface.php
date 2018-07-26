<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 7/25/18
 * Time: 10:49 AM
 */

interface DatabaseRepositoryInterface
{
    /**
     * Gets a single table as an array with tablename as key to an array with first row as column names
     * and all other rows the contents
     * @param $tableNames name of the table
     * @param string $selector columns or just "*" (default)
     * @param string $statements for example "WHERE id=1 or LIMIT 1
     * @return mixed
     * @throws Exception
     */
    public function getTables($tableNames, $selector = "*", $statements = "");

    /**
     * Returns the contents of a table as JSON
     * @param mixed $tableNames (or a single name as string)
     * @param string $selector
     * @param string $statements
     * @return string   JSON
     * @throws Exception
     */
    public function getTablesAsJSON($tableNames, $selector = "*", $statements = "");
}