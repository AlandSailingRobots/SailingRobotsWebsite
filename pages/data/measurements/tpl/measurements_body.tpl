<link rel="stylesheet" href="css/measurements.css" />
<div class="jumbotron">
  <h1>Measurements page</h1>
  <p>On this page you can see measurements data for Aland Sailing Robots.</p>

</div>
<div class="measurements-container">
  <div class="pagination"><?php echo $pagination ?></div>
  <table class="measurements">
    <?php echo '<a href="php/download.php">download</a>' ?>
    <?php echo $measurementsData ?>
</table>
</div>
