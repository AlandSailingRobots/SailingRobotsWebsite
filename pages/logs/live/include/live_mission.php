<?PHP
require('DB_Connection.php');

$db = new DBConnection();

$id = $db->getLatestID();
//$data = $db->getLatestData(end($id));
echo json_encode($id);
echo "heje";

?>