<?php
/**
 * File: printTables.php
 *
 * This function takes the given array and display in a table with the given name.
 *
 * @see https://github.com/AlandSailingRobots/SailingRobotsWebsite
 */

/**
 * Prints tables in HTML
 *
 * @param string $table     Table in DB
 * @param string $tableName Name printed on page?
 *
 * @return void
 */
function printTables($table, $tableName)
{
?>
    <div class='col-xs-12 col-sm-6 col-md-4'>
        <div class="form-group" id="<?php echo $tableName ;?>">
            <div class='panel panel-default'>
                <div class='panel-heading'><?php echo $tableName ;
                ?></div>
                <table class='table'>
<?php
if (is_array($table)) {
    foreach ($table as $key => $value) {
        echo "<tr>\n";
        echo "<td>$key</td>";
        echo "<td>$value</td>\n";
        if ($key != "id") {
            echo "<td><input type='text' class='form-control' name="
                .$tableName.'|'.$key." size='1'></td>";
        } else {
            echo "<td></td>";
        }
        echo "</tr>\n";
    }
} else {
    echo "<tr><td> No data found </td></tr>\n";
}
?>
                
                </table>
            </div>
        </div>
    </div>
<?php
}
?>
