<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Gentallela Alela! | </title>

    <!-- Bootstrap -->
    <link href="../../assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../../assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="../../assets/vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../../assets/css/custom.min.css" rel="stylesheet">

    <style type="text/css">
    body
    {
      background-color: #f4f4f4;
    }
    .submitButton
    {
      margin-left: 80px;
    }
    </style>
  </head>

  <body class="register">
    <div>
      <a class="hiddenanchor" id="signin"></a>
      <a class="hiddenanchor" id="signup"></a>
      <!-- <a class="hiddenanchor" id="signin"></a> -->
              <!-- <h1>Create 1 Account</h1> -->

      <div class="login_wrapper">
        <div id="login" class="animate form ">
              <!-- <h1>Create 3 Account</h1> -->

          <section class="login_content">
            <form action="register_post.php" method="post">
              <h1>Create Account</h1>
              <?php 
                if (isset($_GET['message']))
                {
                  echo '<h3>' . $_GET['message'] . '</h3>';
                }
              ?>
              <div>
                <input type="text" name="username" class="form-control" placeholder="Username" autofocus required="" />
              </div>
              <div>
                <input type="email" name="email" class="form-control" placeholder="Email" required="" />
              </div>
              <div>
                <input type="password" name="password" class="form-control" placeholder="Password" required="" />
              </div>
              <div>
                <input type="password" name="password_confirmed" class="form-control" placeholder="Password Confirmation" required="" />
              </div>
              <div class="text-align">
                <input class="btn btn-default submit" type="submit" name="Submit" value="Register" id="submitButton">
                <!-- <a class="btn btn-default submit" href="index.html">Submit (index)</a> -->
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">Already a member ?
                  <a href="login.php" class="to_register"> Log in </a>
                </p>

                <!-- <div class="clearfix"></div> -->
                <br />
                <?php include '../../include/footer_short.php' ?>

              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
</html>