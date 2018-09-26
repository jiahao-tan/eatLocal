<?php

//if (session_status() !== PHP_SESSION_ACTIVE) {session_start();} for php 5.4 and above

if(session_id() == '' || !isset($_SESSION)){session_start();}

if(!isset($_SESSION["username"])) {
  echo '<h1>Invalid Login! Redirecting...</h1>';
  header("Refresh: 3; url=index.php");
}

if($_SESSION["type"]==="admin") {
  header("location:index.php?page=admin");
}

include 'config.php';

?>


<div class="container">
    <div class="row" style="margin-top:30px;">
      <div class="small-12">
			<?php
				echo '<h3>Hi ' .$_SESSION['fname'] .'</h3>';
			?>

        <h4>Account Details</h4>

        <p>Below are your details in the database. If you wish to change anything, then just enter new data in text box and click on update.</p>
      </div>
    </div>


    <form method="POST" action="index.php?page=update" style="margin-top:30px;" enctype="multipart/form-data">
      <div class="row">
        <div class="small-12">

              <?php

                $result = $mysqli->query("SELECT * FROM users WHERE user_id='".$_SESSION['user_id']."'");

                if($result === FALSE){
                  die($mysqli->error);
                }

                if($result) {
                  $obj = $result->fetch_object();
          		  echo '<div class="row">';
                  echo '<div class="col-sm-3">';
                    echo '<label for="user_id" class="right inline">User id.</label>';
                  echo '</div>';
                  echo '<div class="col-sm-9">';
                  echo '<span id="user_id">'. $obj->fname. '</span>';
                  echo '</div>';
				  echo '</div>';

          		  echo '<div class="row">';
                  echo '<div class="col-sm-3">';
                    echo '<label for="right-label" class="right inline">First Name</label>';
                  echo '</div>';
                  echo '<div class="col-sm-9">';
                  echo '<input type="text" id="right-label" placeholder="'. $obj->fname. '" name="fname">';

                  echo '</div>';
				  echo '</div>';

                  echo '<div class="row">';
                  echo '<div class="col-sm-3">';
                  echo '<label for="right-label" class="right inline">Last Name</label>';
                  echo '</div>';
                  echo '<div class="col-sm-9">';

                  echo '<input type="text" id="right-label" placeholder="'. $obj->lname. '" name="lname">';

                  echo '</div>';
                  echo '</div>';

                  echo '<div class="row">';
                  echo '<div class="col-sm-3">';
                  echo '<label for="right-label" class="right inline">Address</label>';
                  echo '</div>';
                  echo '<div class="col-sm-9">';
                  echo '<input type="text" id="right-label" placeholder="'. $obj->address. '" name="address">';



                  echo '</div>';
                  echo '</div>';

                  echo '<div class="row">';
                  echo '<div class="col-sm-3">';
                  echo '<label for="right-label" class="right inline">Suburb</label>';
                  echo '</div>';
                  echo '<div class="col-sm-9">';
                  echo '<input type="text" id="right-label" placeholder="'. $obj->suburb. '" name="suburb">';
                  echo '</div>';
                  echo '</div>';

                  echo '<div class="row">';
                  echo '<div class="col-sm-3">';
                  echo '<label for="right-label" class="right inline">Postcode</label>';
                  echo '</div>';
                  echo '<div class="col-sm-9">';

                  echo '<input type="text" id="right-label" placeholder="'. $obj->postcode. '" name="postcode">';

                  echo '</div>';
                  echo '</div>';

                  echo '<div class="row">';
                  echo '<div class="col-sm-3">';
                  echo '<label for="right-label" class="right inline">Email</label>';
                  echo '</div>';

                  echo '<div class="col-sm-9">';


                  echo '<input type="email" id="right-label" placeholder="'. $obj->email. '" name="email">';

                  echo '</div>';
                  echo '</div>';

                  echo '<div class="row">';
                  echo '<div class="col-sm-3">';
                  echo '<label for="image" class="right inline">Image</label>';
                  echo '</div>';

                  echo '<div class="col-sm-9">';


					if(file_exists("user_images/".$_SESSION["user_id"]))
						echo '<img src="user_images/'.$_SESSION["user_id"].'">';
					else
						echo "User does not have an image at this point.";
				  //echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"131072\">\n";
                  echo '<input type="file" id="image" name="image">';

                  echo '</div>';
                  echo '</div>';

              }



              echo '<div class="row">';
              echo '<div class="col-sm-3">';
              echo '<label for="right-label" class="right inline">Password</label>';
              echo '</div>';
              echo '<div class="col-sm-9">';
              echo '<input type="password" id="right-label" name="pwd">';

              echo '</div>';
              echo '</div>';
          ?>

          <div class="row">
            <div class="col-sm-3">

            </div>
            <div class="col-sm-9">
              <input type="submit" id="right-label" value="Update" style="background: #0078A0; border: none; color: #fff; font-family: 'Helvetica Neue', sans-serif; font-size: 1em; padding: 10px;">
              <input type="reset" id="right-label" value="Reset" style="background: #0078A0; border: none; color: #fff; font-family: 'Helvetica Neue', sans-serif; font-size: 1em; padding: 10px;">
            </div>
          </div>
        </div>
      </div>
    </form>


    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
    </script>
</div>
