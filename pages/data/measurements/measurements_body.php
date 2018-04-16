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
  $_GET['page'] = 1;
}


#SET limit for rows, offset and how many link buttons for pagination
$limit = 50;
$offset = ($_GET['page'] -1) * $limit;


$measurements->__set('limit', $limit);
$measurements->__set('offset', $offset);

$measurementsData = $measurements->__toString();

$pager = New Pager($measurements->getPages($measurements->fetch_table));

$pager->__set('currentPage', $currentPage);
$pagesToShow = 10;
$pager->__set('pagesToShow', $pagesToShow);
$pagination = $pager->__toString();

include 'tpl/measurements_body.tpl';

?>
