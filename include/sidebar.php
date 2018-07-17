<div class="main_container">
      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">
          <div class="navbar nav_title" style="border: 0;">
            <a href="<?php echo $relative_path ?>" class="site_title"><i class="fa fa-ship"></i> <span>Sailing Robots</span></a>
          </div>

          <div class="clearfix"></div>

          <!-- menu profile quick info -->
          <div class="profile">
            <div class="profile_pic">
                <?php
                $face_pic = 'resources/users/default.png';
              //echo '####### ' . __ROOT__ . $face_pic;
                if (file_exists(__ROOT__ . '/resources/users/' . $_SESSION['username'] . '/user.jpg')) {
                    $face_pic = 'resources/users/' . $_SESSION['username'] . '/user.jpg';
                } elseif (file_exists(__ROOT__ . '/resources/users/' . $_SESSION['username'] . '/user.png')) {
                    $face_pic = 'resources/users/' . $_SESSION['username'] . '/user.png';
                }

              // $_SESSION['face_pic'] = realpath($face_pic);
                $_SESSION['face_pic'] = $face_pic;
                echo '<img src="' . $relative_path . $_SESSION['face_pic'] . '" alt="Face Picture" class="img-circle profile_img" />';
                ?>

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
                <li><a href=" <?php echo $relative_path . "index.php" ?>"><i class="fa fa-home"></i> Home </a>
                  <!-- <ul class="nav child_menu">
                  <li><a >Dashboard</a></li>
                  </ul> -->
                </li>

                <!-- First Menu -->
                <li>
                  <a><i class="fa fa-bar-chart-o"></i>Data Presentation<span class="fa fa-chevron-down"></span><span class="label label-info pull-right">WIP</span></a>
                  <ul class="nav child_menu">
                    <li><a href="<?php echo $relative_path . "pages/data/measurements/index.php"?>">Measurements</a></li>
                  </ul>
                </li>

                <!-- Second Menu -->
                <li>
                  <a><i class="fa fa-desktop"></i>Configuration<span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                  <li><a href=" <?php echo $relative_path . "pages/configuration/sailingRobot/index.php?boat=aspire"?>">Configure ASPire</a></li>
                  <li><a href=" <?php echo $relative_path . "pages/configuration/sailingRobot/index.php?boat=janet"?>">Configure Janet</a></li>
                    <li><a href="<?php echo $relative_path . "pages/configuration/mission/index.php"?>">Mission</a></li>
                  </ul>
                </li>

                <!-- Third Menu -->
                <li>
                  <a><i class="fa fa-table"></i>Logs<span class="fa fa-chevron-down"></span><span class="label label-info pull-right">WIP</span></a>
                  <ul class="nav child_menu">
                    <li><a href="<?php echo $relative_path . "pages/logs/live/index.php"?>">Live Logs<span class="label label-warning pull-right">Coming Soon</span></a></li>
                    <li><a href="<?php echo $relative_path . "pages/logs/saved_logs/index.php?boat=aspire"?>">ASPire Saved Logs<span class="label label-success pull-warning pull-right">WIP</span></a></li>
                    <li><a href="<?php echo $relative_path . "pages/logs/saved_logs/index.php?boat=janet"?>">Janet Saved Logs<span class="label label-success pull-warning pull-right">Done</span></a></li>
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
          <a data-toggle="tooltip" data-placement="top" title="Logout" href="<?php echo $relative_path . 'pages/users/logout.php' ?>" >
            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
          </a>
        </div>
        <!-- /menu footer buttons -->
      </div>
      <!-- /sidebar -->
  </div>
