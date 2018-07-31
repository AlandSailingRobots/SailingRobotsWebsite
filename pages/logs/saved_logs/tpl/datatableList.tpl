<style>
    li.dtList > a {
        font-size: 8pt;
    }

</style>

<div class="col-sm-1 col-md-2 sidebar">
    <ul class="nav nav-sidebar">
        <?php echo $dtList ?>
    </ul>
    <button id="timeoutBtn" onclick="toggleTimeout"><i id="timeoutRefresh" class="fa fa-refresh"></i></button>
</div>
