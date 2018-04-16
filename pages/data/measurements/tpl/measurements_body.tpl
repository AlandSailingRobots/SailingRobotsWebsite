<link rel="stylesheet" href="css/measurements.css" />
<div class="jumbotron">
  <h1>Measurements page</h1>
  <p>On this page you can see measurements data for Aland Sailing Robots.</p>

</div>
<?php /** font awesome icons **/
  $fa = '<i class="fa fa-file"></i>'; ?>
<?php echo '<a href="php/download.php?type=xlsx"><button type="button" class="btn btn-default">'.$fa.' Download XLSX</button></a>' ?>
<?php echo '<a href="php/download.php?type=ods"><button type="button" class="btn btn-default">'.$fa.' Download ODS</button></a>' ?>
<?php echo '<a href="php/download.php?type=csv"><button type="button" class="btn btn-default">'.$fa.' Download CSV</button></a>' ?>
<div class="measurements-container">

  <div class="pagination"><?php echo $pagination ?></div>
  <table class="measurements">

    <?php echo $measurementsData ?>
</table>
</div>
