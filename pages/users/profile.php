<?php
  session_start();
  define('__ROOT__', dirname(dirname(dirname(__FILE__))));
  require_once(__ROOT__.'/globalsettings.php');
  $relative_path = './../../';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shorcut icon" href="../../resources/images/forward_enabled_hover.png">
    <title>Sailing Robots </title>

    <!-- Bootstrap -->
    <link href="../../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="../../assets/css/custom.min.css" rel="stylesheet">

  </head>

<body class="nav-md">
  <div class="container body">
    <!-- sidebar -->
    <?php include '../../include/sidebar.php'; ?>
    <!-- /sidebar -->

    <!-- top navigation -->
    <?php include '../../include/top_navigation.php'; ?>
    <!-- /top navigation -->

    <!-- page content -->
    <div class="right_col" role="main">
      <div class="">
        <div class="page-title">
          <div class="title_left">
            <h3>User Profile</h3>
          </div>

          <div class="title_right">
            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Search for...">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button">Go!</button>
                </span>
              </div>
            </div>
          </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>User Report <small>Activity report</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                  </li>
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    <ul class="dropdown-menu" role="menu">
                      <li><a href="#">Settings 1</a>
                      </li>
                      <li><a href="#">Settings 2</a>
                      </li>
                    </ul>
                  </li>
                  <li><a class="close-link"><i class="fa fa-close"></i></a>
                  </li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">

                <div class="col-md-3 col-sm-3 col-xs-12 profile_left">

                  <div class="profile_img">

                    <!-- end of image cropping -->
                    <div id="crop-avatar">
                      <!-- Current avatar -->
                      <img class="img-responsive avatar-view" src=<?php echo $GLOBALS['server'] . $_SESSION['face_pic'] ?> alt="Avatar" title="Change the avatar">

                      <!-- Cropping modal -->
                      <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <form class="avatar-form" action="crop.php" enctype="multipart/form-data" method="post">
                              <div class="modal-header">
                                <button class="close" data-dismiss="modal" type="button">&times;</button>
                                <h4 class="modal-title" id="avatar-modal-label">Change Avatar</h4>
                              </div>
                              <div class="modal-body">
                                <div class="avatar-body">

                                  <!-- Upload image and data -->
                                  <div class="avatar-upload">
                                    <input class="avatar-src" name="avatar_src" type="hidden">
                                    <input class="avatar-data" name="avatar_data" type="hidden">
                                    <label for="avatarInput">Local upload</label>
                                    <input class="avatar-input" id="avatarInput" name="avatar_file" type="file">
                                  </div>

                                  <!-- Crop and preview -->
                                  <div class="row">
                                    <div class="col-md-9">
                                      <div class="avatar-wrapper"></div>
                                    </div>
                                    <div class="col-md-3">
                                      <div class="avatar-preview preview-lg"></div>
                                      <div class="avatar-preview preview-md"></div>
                                      <div class="avatar-preview preview-sm"></div>
                                    </div>
                                  </div>

                                  <div class="row avatar-btns">
                                    <div class="col-md-9">
                                      <div class="btn-group">
                                        <button class="btn btn-primary" data-method="rotate" data-option="-90" type="button" title="Rotate -90 degrees">Rotate Left</button>
                                        <button class="btn btn-primary" data-method="rotate" data-option="-15" type="button">-15deg</button>
                                        <button class="btn btn-primary" data-method="rotate" data-option="-30" type="button">-30deg</button>
                                        <button class="btn btn-primary" data-method="rotate" data-option="-45" type="button">-45deg</button>
                                      </div>
                                      <div class="btn-group">
                                        <button class="btn btn-primary" data-method="rotate" data-option="90" type="button" title="Rotate 90 degrees">Rotate Right</button>
                                        <button class="btn btn-primary" data-method="rotate" data-option="15" type="button">15deg</button>
                                        <button class="btn btn-primary" data-method="rotate" data-option="30" type="button">30deg</button>
                                        <button class="btn btn-primary" data-method="rotate" data-option="45" type="button">45deg</button>
                                      </div>
                                    </div>
                                    <div class="col-md-3">
                                      <button class="btn btn-primary btn-block avatar-save" type="submit">Done</button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- <div class="modal-footer">
                                                <button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
                                              </div> -->
                            </form>
                          </div>
                        </div>
                      </div>
                      <!-- /.modal -->

                      <!-- Loading state -->
                      <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
                    </div>
                    <!-- end of image cropping -->

                  </div>
                  <h3>Samuel Doe</h3>

                  <ul class="list-unstyled user_data">
                    <li><i class="fa fa-map-marker user-profile-icon"></i> San Francisco, California, USA
                    </li>

                    <li>
                      <i class="fa fa-briefcase user-profile-icon"></i> Software Engineer
                    </li>

                    <li class="m-top-xs">
                      <i class="fa fa-external-link user-profile-icon"></i>
                      <a href="http://www.kimlabs.com/profile/" target="_blank">www.kimlabs.com</a>
                    </li>
                  </ul>

                  <a class="btn btn-success"><i class="fa fa-edit m-right-xs"></i>Edit Profile</a>
                  <br />

                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /page content -->


    <!-- footer content -->
    <?php include '../../include/footer.php'; ?>
    <!-- /footer content -->
  </div>

  <!-- ##########################    JAVASCRIPT     ########################## -->
  <?php // Not very clean, but the default configs includes too many JS for a beginner
        // That way, main file is 'clean' ?>
  <?php //include '../../include/js_scripts.php'; ?>
    <!-- jQuery -->
    <script src="../../assets/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../../assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../../assets/js/custom.min.js"></script>

  <!-- ##########################    JAVASCRIPT     ########################## -->
</body>
</html>
