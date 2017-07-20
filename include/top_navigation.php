<div class="top_nav">
      <div class="nav_menu">
        <nav class="" role="navigation">
          <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
          </div>
          <ul class="nav navbar-nav navbar-right">
            <?php

            if (isset($_SESSION['id']) AND isset($_SESSION['username']))
            {
              echo '<li class="">';
            ?>
                
                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  <img src=<?php echo $relative_path . $_SESSION['face_pic'] ?> alt=""><?php echo $_SESSION['username'] ?>
                  
                  <span class=" fa fa-angle-down"></span>
                </a>
                <ul class="dropdown-menu dropdown-usermenu pull-right">
                  <li><a href=<?php echo $relative_path . 'pages/users/profile.php' ?>> Profile</a></li>
                  <li>
                    <a href="javascript:;">
                      <span class="badge bg-red pull-right">50%</span>
                      <span>Settings</span>
                    </a>
                  </li>
                  <li><a href="javascript:;">Help</a></li>
                  <li><a href= <?php echo $relative_path . 'pages/users/logout.php' ?> ><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                </ul>
                </li>
                <li role="presentation" class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope-o"></i>
                    <!-- <span class="badge bg-green">6</span> -->
                  </a>
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                    <li>
                      <div class="text-center">
                        <a>
                          <strong>See All Alerts</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li>
            <?php
            }
            else
            {
              echo '<li class="">';
            ?>

                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  <img src= <?php echo $relative_path . 'resources/users/default.png'?> alt="">Guest
                  <span class=" fa fa-angle-down"></span>
                </a>
                <ul class="dropdown-menu dropdown-usermenu pull-right">
                  <li><a href="javascript:;">Help</a></li>
                  <li>
                    <a href=<?php echo $relative_path . 'pages/users/login.php' ?>><i class="fa fa-sign-in pull-right"></i>Log In</a>
                  </li>
                </ul>
              </li>
            <?php
            }
            ?>

          </ul>
        </nav>
      </div>
  </div>
