<link rel="stylesheet" href="css/measurements.css" />
<div class="jumbotron">
  <h1>Measurements page</h1>
  <p>On this page you can see measurements data for Aland Sailing Robots.</p>

</div>

<?php echo '<a href="php/download.php?type=xlsx"><button type="button" class="btn btn-default">Download XLSX</button></a>' ?>
<?php echo '<a href="php/download.php?type=csv"><button type="button" class="btn btn-default">Download CSV</button></a>' ?>
<div class="measurements-container">

  <div class="pagination"><?php echo $pagination ?></div>
  <table class="measurements">

    <?php echo $measurementsData ?>
</table>
</div>
