<?php
// ASPire

function pushAllLogs($boat, $data) 
{
    $db = $GLOBALS['db_connection'];
    $data = json_decode($data,true);

    if (!empty($data))
    {
        foreach ($data as $table_name => $table) 
        {
            // Generate the array to be bind with the prepared SQL query
            foreach ($table as $id_log => $log) 
            {
                $param_stmt = "(";
                $param_to_fill = "(";
                $param_array = array();
                foreach ($log as $column_name => $value) 
                {
                    $param_array[$column_name] = $value;
                    $param_stmt = $param_stmt . ''.$column_name .',' ;
                    $param_to_fill = $param_to_fill . ':'.$column_name.',';
                }

                // Remove the extra comma
                $param_stmt = substr($param_stmt, 0, -1).')'; 
                $param_to_fill = substr($param_to_fill, 0, -1).')';// Now whe hace something like (?, ?, ?, ?)

                $param_array['id'] = NULL;
                // Prepare the SQL Query
                $query = $db->prepare("INSERT INTO $table_name $param_stmt VALUES $param_to_fill ;");
                $query->execute($param_array);
            }

        }     
        //$result = array('result' => 1);
        //return json_encode($result);
    }
}
