<style>
    #datatables {
        font-size: 10pt;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9 col-md-10 main">
            <h1 class="sub-header jumbotron"><?php echo $page_title ;?></h1>
            <div id="dataTable"  class="table-responsive">

                <table id="datatables" class="display" width="100%" cellspacing="0">
                    <thead>
                    <tr id="dtHeaders">
                        <?php echo $dtHeaders ?>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr id="dtHeaders">
                        <?php #echo $dtHeaders ?>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>