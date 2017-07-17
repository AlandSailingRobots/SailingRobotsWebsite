<div id='table' class='col-lg-4'>
    <table class="table table-striped">
    <tbody>
    <?php
        if(isset($_GET["id"]) && isset($_GET["name"]) && isset($_GET["table"]) && $_GET["number"])
        {
            $id = $_GET["id"];
            $name = $_GET["name"];
            $table = $_GET["table"];
            $number = $_GET["number"];
            // $_SESSION['number'] = $number;
            // $_SESSION['idd'] = $id;
            // $_SESSION['name'] = $name;
            // $_SESSION['table'] = $table;
            $result = getAll($id, $name, $table);
            if (!empty($result))
            {
                foreach ($result[0] as $key => $value) 
                {
                    if (is_string($key))
                    {
                        echo '<tr>';
                            echo '<th>' . $key . '</th>';
                            echo '<td>' .  $value . '</td>';
                        echo '</tr>';
                    }
                }
            }
            else
            {
            ?>  
                <tr>
                    <td>ERROR:</td>
                    <td>Table empty; gps_datalogs id does not have a corresponding system_dataLogs entry</td>
                </tr>
            <?php
            }
        }
        else 
        {
        ?>
            <tr>
                <td>ID | TABLE | NAME | NUMBER has not been set</td>
            </tr>
        <?php
        }
    ?>

    </tbody>
    </table>
</div>