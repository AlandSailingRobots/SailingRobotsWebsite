<?php
  $relative_path = './../../';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php // Head of the HTML document
    include $relative_path . 'include/head.php';
?>
</head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form action="login_post.php" method="post">
              <h1>Login Form</h1>
                <?php
                if (isset($_GET['message'])) {
                    echo '<h3>' . $_GET['message'] . '</h3>';
                }
                ?>
              <div>
                <input type="text" class="form-control" placeholder="Username" name="username" required="" autofocus />
              </div>
              <div>
                <input type="password" class="form-control" name="password" placeholder="Password" required="" />
              </div>
              <div>
                <!-- <a class="btn btn-default submit" href="index.html">Log in</a> -->
                <input class="btn btn-default submit" type="submit" name="Submit" value="Log-in" id="submitButton">
                <a class="reset_pass" href="#" title="Not implemented yet">Lost your password?</a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">New to site?
                  <a href="register.php" class="to_register"> Create Account </a>
                </p>

                <div class="clearfix"></div>
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
