<?php

include 'conf/db.php';
include 'header.php';

$failed_login = '';

// If someone tries to post a login, this should allow them to login
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $user = filter_var($_POST["user"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
    $passwd = trim($_POST["passwd"]);

    if ($debug) { echo "QUERY: SELECT passwd FROM users WHERE email = '$user'"; }

    $res = pg_query($pgh,"SELECT passwd,fname FROM users WHERE email = '$user'");
    if (!$res) {
        if ($debug) { echo " :: ", pg_last_error($pgh), "<p>"; }
    }

    $userRecord = pg_fetch_row($res);
    if ($debug) {
        echo "<p>UserRecord: $userRecord[0]<p>";
        echo "<p>LoginRecord: $user / $passwd<p>";
    }

    if (password_verify("$passwd","$userRecord[0]")) {
        $_SESSION['user'] = $_POST['user'];
        $_SESSION['name'] = $userRecord[1];
    } else {
        if ($debug) { echo "<h2>Password check for $user failed</h2>"; }
        $failed_login = '<span class="failed_login">Login Failed</span>';
    }

}

// Check for an established session
if (!isset($_SESSION['user'])) {
    ?>

       <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
       <div id="login-unauth">

             <form action="index.php" method="post" class="user_login">
                <ul>
  		   <li><input class="login" name="user" type="email" placeholder="you@domain.com" required></li>
		   <li><input class="login" name="passwd" type="password" placeholder="password" required></li>
		</ul>
	 	<ul>
		    <li><button class="fortune_button" type="submit" name="login_button">LOGIN</button></li>
	            <li><?=$failed_login?></li>
		</ul>
             </div>

             <div id="create_account">
		<a href="javascript:void(0);" class="fortune_button" onClick="makePopUp('create_account.php','Create Account');return false;">Create Account</a>
	     </div>
        </div>

<?php
} else {

    /*
     * If you're already logged in, lets show the management page.
     *	We'll start with the SQL query then start dumping HTML
     */

    $res = pg_query($pgh,"SELECT * FROM FORTUNES");
    if (!$res) {
        if ($debug) { echo " :: ", pg_last_error($pgh), "<p>"; }
    }
    $rows = pg_num_rows($res);
    ?>

	<div class="login-auth">
             <div class="account_text"><span class="account_header">User:</span> <?php echo $_SESSION['user']; ?><p></div><p>
             <div class="account_text"><span class="account_header">Name:</span> <?php echo $_SESSION['name']; ?><p></div><p>
	     <button id="fortuneShow" class="fortune_button">ADD FORTUNE</button>
             <a class="fortune_button" href="logout.php">LOGOUT</a>
	     </p>
        </div>
	<p>

<?php
}
include 'footer.php';
