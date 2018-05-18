<?php
// ASPire
// Decode the json array and send queries to the DB to update the configuration of the boat
function pushConfigs($data)
{
    $db = $GLOBALS['db_connection'];
    $data = json_decode($data, true);
    $id = 1;
    // Shorter version, PDO style
    foreach ($data as $table_name => $table) {
        $param_stmt = "";
        // Generate the array to be bind with the prepared SQL query
        $param_array = array();
        foreach ($table as $column_name => $value) {
            // Patch b/c Marc changed its DB compared to the website
            $column_name = $column_name == "is_checkpoint" ? "isCheckpoint" : $column_name;

            $param_array[$column_name] = $value;

            $param_stmt = $param_stmt . ' ' . $column_name . '= :'.$column_name . ',';
        }
        // Remove the extra comma
        $param_stmt = substr($param_stmt, 0, -1);

        // Prepare the SQL Query
        $query = $db->prepare('UPDATE '.$table_name . ' SET ' . $param_stmt  .';');
        $query->execute($param_array);
        // $query->close()
    }
    return 1;
}
