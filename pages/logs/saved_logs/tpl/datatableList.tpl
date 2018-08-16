<style>
    li.dtList > a {
        font-size: 10pt;
        font-style: bold;
    }

</style>

<div class="col-sm-1 col-md-2 sidebar">
    <ul id="DataLog" class="nav nav-sidebar">
        <?php echo $dtList ?>
    </ul>
    <button id="timeoutBtn" onclick="toggleTimeout"><i id="timeoutRefresh" class="fa fa-refresh"></i></button>
</div>
