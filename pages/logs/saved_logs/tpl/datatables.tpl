<style>
    #datatables {
        font-size: 10pt;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9 col-md-10 main">
            <h1 class="sub-header jumbotron"><?php echo $view['pageTitle'] ;?></h1>
            <div id="dataTable"  class="table-responsive">

                <table id="datatables" class="table display" width="100%" cellspacing="0">
                    <thead>
                    <tr id="dtHeaders">
                        <?php echo $view['dtHeaders'] ?>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr id="dtHeaders">
                        <?php echo $view['dtHeaders'] ?>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>