<?php
require_once('php/measurements.php');


$measurements = New Measurements();
$measurements->__set('boatName', 'aspire');
$measurements->prepare();
$measurementsData = $measurements->__toString();

include 'tpl/measurements_body.tpl';
?>
