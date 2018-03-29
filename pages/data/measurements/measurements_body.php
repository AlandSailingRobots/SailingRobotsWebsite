<?php
require_once('php/measurements.php');
include 'tpl/measurements_body.tpl';

$measurements = New Measurements();
$measurements->__set('boatName', 'aspire');
$measurements->prepare();
echo $measurements->__toString();

?>
