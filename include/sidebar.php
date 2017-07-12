<div class="main_container">
  <div class="col-md-3 left_col">
    <div class="left_col scroll-view">
      <div class="navbar nav_title" style="border: 0;">
        <a href=<?php echo $GLOBALS['server'] ?> class="site_title"><i class="fa fa-ship"></i> <span>Sailing Robots</span></a>
      </div>

      <div class="clearfix"></div>

      <!-- menu profile quick info -->
      <div class="profile">
        <div class="profile_pic">
          <?php
          $face_pic =  'resources/users/' . $_SESSION['username'] . '/user.jpg';
          //echo '####### ' . __ROOT__ . $face_pic;
          if (!(file_exists(__ROOT__ . '/' . $face_pic)))
          {
            $face_pic = 'resources/users/default.png';
          }

          // TODO Fix the access danger. I think it comes from read-right on disk.
          // $_SESSION['face_pic'] = realpath($face_pic);
          $_SESSION['face_pic'] = $face_pic;

          ?>
          <img src=<?php echo $relative_path . $_SESSION['face_pic'] ?> alt="..." class="img-circle profile_img">
        </div>
        <div class="profile_info">
          <span>Welcome,</span>
          <?php echo '<h2>' . $_SESSION['username'] . '</h2>'?>
        </div>
      </div>
      <!-- /menu profile quick info -->

      <br />

      <!-- sidebar menu -->
      <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
        <div class="menu_section">
          <h3>General</h3>
          <ul class="nav side-menu">
            <li><a href= <?php echo $relative_path . "index.php" ?>><i class="fa fa-home"></i> Home </a>
              <!-- <ul class="nav child_menu">
              <li><a >Dashboard</a></li>
              </ul> -->
            </li>

            <li><a><i class="fa fa-bar-chart-o"></i> Data Presentation <span class="label label-success pull-right">Coming Soon</span><span class="fa fa-chevron-down"></span></a>
              <ul class="nav child_menu">
                <li><a href="#">Chart JS</a></li>
              </ul>
            </li>

            <li><a><i class="fa fa-desktop"></i> Configuration <span class="fa fa-chevron-down"></span><span class="label label-warning pull-right">Soon</span></a>
              <ul class="nav child_menu">
              <li><a href= <?php echo $relative_path . "pages/configuration/sailingRobot/index.php"?>>Sailing Robot<span class="label label-danger pull-right">In Progress !</span></a></li>
                <li><a href="#">Mission<span class="label label-success pull-right">Coming Soon</span></a></li>
              </ul>
            </li>
            <li><a><i class="fa fa-table"></i> Logs <span class="label label-info pull-right">Work in Progress</span><span class="fa fa-chevron-down"></span></a>
              <ul class="nav child_menu">
                <li><a href=<?php echo $relative_path . "pages/logs/live/index.php"?>>Live Logs<span class="label label-success pull-right">Coming Soon</span></a></li>
              </ul>
            </li>
          </ul>

        </div>
      </div>
      <!-- /sidebar menu -->
    </div>

    <!-- /menu footer buttons -->
    <div class="sidebar-footer hidden-small">
      <a data-toggle="tooltip" data-placement="top" title="Settings">
        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="FullScreen">
        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="Lock">
        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="Logout">
        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
      </a>
    </div>
    <!-- /menu footer buttons -->
  </div>
  <!-- /sidebar -->
</div>
