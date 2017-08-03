<?php
function printTables($table, $tableName)
{?>
    <div class='col-xs-12 col-sm-6 col-md-4'>
        <div class="form-group" id="<?php echo $tableName ;?>">
            <div class='panel panel-default'>
                <div class='panel-heading'><?php echo $tableName ;
                ?></div>
                <table class='table'>
            <?php
            if (is_array($table))
            {
                foreach($table as $key => $value)
                {
                ?>  
                    <tr>
                        <td><?php echo $key ; ?></td>
                        <td><?php echo $value ; ?></td>
                    <?php
                    if ( $key != "id")
                    {
                        echo "    <td><input type='text' class='form-control' name=".$tableName.'|'.$key." size='1'></td>";
                    }
                    else
                    {
                        echo "    <td></td>";
                    }
                echo '
                    </tr>';
                }
            }
            else
            {
            ?><tr>
                <td> No data found </td>
            <?php
            }
            // echo '</tr>';
            ?>
                
                </table>
            </div>
        </div>
    </div>
<?php
}
?>
