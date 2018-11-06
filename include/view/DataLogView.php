<?php
/**
 * Created by PhpStorm.
 * User: jan
 * Date: 2018-08-07
 * Time: 18:41
 */

class DataLogView {

    public static function buildList (string $jsonResponse, $boatName): string {
        $tblList = "";
        $array = json_decode($jsonResponse, true);
        reset($array);
        $firstKey = key($array);

        $patterns = array();
        array_push($patterns, '/dataLogs_/');
        array_push($patterns, '/_/');

        $replacements = array();
        array_push($replacements, '');
        array_push($replacements, ' ');

        $listOfAbbreviation = array(
            "GPS" => "gps",
        );


        foreach ($array[$firstKey] as $tableName) {
            $displayName = self::prepareTableNames($patterns, $replacements, $tableName[0], $listOfAbbreviation);
            $displayName = $displayName . ' Data';

            //index.php?boat=aspire&data=gps
            $tblList = $tblList . '<li class="dtList "><a id="dataLogList" class="dtLstLnk" href="index.php?boat=' . $boatName
                .'&data=' . $tableName[0]
                . '" dt="true">'
                . ucwords($displayName) . '</a></li>';

            /**
            $tblList = $tblList . '<li class="dtList "><a id="dataLogList" class="dtLstLnk" href="" boat="' . $boatName
                .'" dataLog="' . $tableName[0]
                . '" dt="true">'
                . ucwords($displayName) . '</a></li>';
             * */
            //$tblList = $tblList . '<li class="dtList"><a href="index.php?boat='.$boatName.'&data='.$tableName[0].'">'. ucwords($displayName) . '</a></li>';

        }
        return $tblList;
    }


    public static function buildHeaders (string $jsonResponse): string {
        $tblHeaders = "";
        $array = json_decode($jsonResponse, true);
        reset($array);
        $firstKey = key($array);
        foreach ($array[$firstKey][0] as $tableName) {
            $tblHeaders = $tblHeaders . "<th>" . $tableName . "</th>";

        }
        return $tblHeaders;
    }

    /**
     * @param array $patterns
     * @param array $replacements
     * @param array $subject
     * @param array $abbreviations
     * @return null|string|string[]
     */
    private static function prepareTableNames(array $patterns, array $replacements, $subject, array $abbreviations) {
        $displayName =  preg_replace($patterns, $replacements, $subject);
        if (in_array($displayName, $abbreviations)) {
            $displayName = strtoupper($displayName);
        } else {
            $displayName = ucwords($displayName);
        }
        return $displayName;
    }
}