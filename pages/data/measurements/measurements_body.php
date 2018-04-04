<?php
require_once('php/measurements.php');


$measurements = New Measurements();

$measurements->__set('boatName', 'aspire');
$measurements->prepare();

$currentPage = null;
if(isset($_GET['page'])) {
  $currentPage = $_GET['page'];
} else {
  $currentPage = 1;
}


#SET limit for rows, offset and how many link buttons for pagination
$limit = 50;
$offset = ($_GET['page'] -1) * $limit;


$measurements->__set('limit', $limit);
$measurements->__set('offset', $offset);


$measurementsData = $measurements->__toString();

$pager = New Pager($measurements->getPages('ithaax_ASPire_config.dataLogs_marine_sensors'));

#echo $currentPage;
$pager->__set('currentPage', $currentPage);
$pagesToShow = 10;
$pager->__set('pagesToShow', $pagesToShow);
#echo $pager->__get('currentPage');
#echo $pager->__get('pages');
$pagination = $pager->__toString();

include 'tpl/measurements_body.tpl';
?>
