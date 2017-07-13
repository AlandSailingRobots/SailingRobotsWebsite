<?php
  $relative_path = './../../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php // Head of the HTML document
    include $relative_path . 'include/head.php'; 
?>

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