<?php

//***************************************************************************************
//
// Purpose:
//     Provides global server settings (and potentially other things in the future).
//    Make sure you include it in all pages that might need it (e.g. database handlers).
//    The commented lines are for hostǵator so if you make any changes that you have to push
//    up there, make sure you reverse the commenting.
//
// Developer Notes:
//
//
//**************************************************************************************/



    /* $GLOBALS['server'] = "http://localhost/tests/Tuto_OCR/SailingRobotsWebsite/"; */
    //$GLOBALS['server'] = "http://www.sailingrobots.com/testdata/";
    $GLOBALS['username'] = 'root';
    //$GLOBALS['username'] = 'ithaax_testdata';
    $GLOBALS['password'] = '';
    //$GLOBALS['password'] = 'test123data';
    $GLOBALS['hostname'] = '127.0.0.1';

    $GLOBALS['database_name_testdata']  = 'ithaax_testdata';
    $GLOBALS['database_name']           = 'ithaax_website_config';
    $GLOBALS['database_ASPire']         = 'ithaax_ASPire_config';
    $GLOBALS['database_mission']        = 'ithaax_mission';
    // $hostname = '127.0.0.1';
    // $db_user = 'root';
    // $db_password = '';
?>
