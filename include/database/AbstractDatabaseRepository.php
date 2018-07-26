<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 7/26/18
 * Time: 10:06 AM
 */

include 'DatabaseRepositoryInterface.php';

abstract class AbstractDatabaseRepository implements DatabaseRepositoryInterface {
    private $pdo = null;
    private $stmt = null;
    protected $dbName = null;

    /**
     * DatabaseRepository constructor.
     * @param DatabaseConnection $databaseConnection
     */
    public function __construct (DatabaseConnection $databaseConnection) {
        try {
            $dsn = $databaseConnection->getDsn();
            $usr = $databaseConnection->getUser();
            $pwd = $databaseConnection->getPassword();
            $opt = $databaseConnection->getOptions();

            $this->dbName = $databaseConnection->getDbName();
            $this->pdo = new PDO($dsn, $usr, $pwd, $opt);

        } catch (PDOException $e) {
            print $e->getMessage();
        }
    }

    /**
     * Gets a single table as an array with tablename as key to an array with first row as column names
     * and all other rows the contents
     * @param $tableNames name of the table
     * @param string $selector columns or just "*" (default)
     * @param string $statements    for example "WHERE id=1 or LIMIT 1
     * @return array
     * @throws Exception
     */
    final public function getTables($tableNames, $selector = "*", $statements = ""): array
    {
        $array = array();

        if (!is_array($tableNames)) {
            $tables = array();
            $tables[] = $tableNames;
            $array = self::getTables($tables, $selector, $statements);
        } else {
            $db = $this->pdo;
            foreach ($tableNames as $tableName) {
                $req = $db->prepare("SELECT $selector FROM $tableName $statements");
                $preResult = $req->execute();
                if (!$preResult) {
                    throw new Exception("Database Error [{$db->errno}] {$req->error}");
                }
                $result = $req->fetchAll(PDO::FETCH_ASSOC);
                if (sizeof($result)) {
                    $array[$tableName][] = array_keys($result[0]);

                    foreach ($result as $r) {
                        $row = array();
                        $j = 0;
                        foreach (array_keys($result[0]) as $key) {
                            $row[$j++] = $r[$key];
                        }
                        $array[$tableName][] = $row;
                    }
                }
            }
        }
        return $array;
    }

    /**
     * Returns the contents of a table as JSON
     * @param mixed $tableNames (or a single name as string)
     * @param string $selector
     * @param string $statements
     * @return string   JSON
     * @throws Exception
     */
    final public function getTablesAsJSON($tableNames, $selector = "*", $statements = ""): string {
        return json_encode(self::getTables($tableNames, $selector, $statements));
    }
}