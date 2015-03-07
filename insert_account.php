<?php

    include 'conf/db.conf';
    include 'header.php';

    if ($debug) { print_r($_REQUEST); }

    // Input validation is good
    $email = filter_var($_POST["email"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
    $lname = filter_var($_POST["lname"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
    $fname = filter_var($_POST["fname"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
    $role = filter_var($_POST["role"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
    $passwd = password_hash(trim($_POST["passwd"]), PASSWORD_BCRYPT);

    if (!$email) {
	die("Missing data for $email");
    }
    if (!$fname) {
	die("Missing data for $fname");
    }
    if (!$lname) {
	die("Missing data for $lname");
    }
    if (!$passwd) {
	die("Missing data for $passwd");
    }
    if (!$role) {
	die("Missing data for $role");
    }

    if ($debug) {
      echo "Query is $pgh<p>";
  	  echo "INSERT INTO users (email,fname,lname,passwd,role,active) VALUES ('$email','$fname','$lname','$passwd','$role',true)<p>";
    }

    $res = pg_query($pgh,"INSERT INTO users (email,fname,lname,passwd,role,active) VALUES ('$email','$fname','$lname','$passwd','$role',true);");
    if (!$res) {
         $result_message="An error occured and we were unable add user $email, please try again later.";
         if ($debug) { echo " :: ", pg_last_error($pgh), "<p>"; }
    } else {
         $result_message="Your account for <?=$email?> has been successfully created. Please go back to the main page and login to continue.";
    }
?>

        <div class="account_created">
	  <?=$result_message?>
	  <p>
          <button type="submit" class="fortune_button" onClick="window.close(); return false;">CLOSE</button>
	</div>
<?php

   include 'footer.php';
