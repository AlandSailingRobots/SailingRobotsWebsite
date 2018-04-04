<?php
require_once('php/measurements.php');


$measurements = New Measurements();

$measurements->__set('boatName', 'aspire');
$measurements->prepare();
$limit = 50;
$offset = ($_GET['page'] -1) * $limit;
$measurements->__set('offset', $offset);
$measurements->__set('limit', $limit);


$measurementsData = $measurements->__toString();

$pager = New Pager($measurements->getPages('ithaax_ASPire_config.dataLogs_marine_sensors'));
$currentPage = null;
if(isset($_GET['page'])) {
  $currentPage = $_GET['page'];
} else {
  $currentPage = 1;
}
#echo $currentPage;
$pager->__set('currentPage', $currentPage);
#echo $pager->__get('currentPage');
#echo $pager->__get('pages');
$pagination = $pager->__toString();

include 'tpl/measurements_body.tpl';
?>
