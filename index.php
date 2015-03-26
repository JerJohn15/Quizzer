<?php

include 'conf/db.conf';
include 'header.php';

$failed_login = '';
/*
 * @author - Jeremiah
 *  Added conditions for redirecting users to a page to take or grade
 *  exam depending on user role (admin or user)
 */

// If someone tries to post a login, this should allow them to login
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $user = filter_var($_POST["user"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
    $passwd = trim($_POST["passwd"]);

    if ($debug) { echo "QUERY: SELECT passwd FROM users WHERE email = '$user'"; }

    $res = pg_query($pgh,"SELECT passwd,fname,role FROM users WHERE email = '$user'");
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
        $_SESSION['role'] = $userRecord[2];
    } else {
        if ($debug) { echo "<h2>Password check for $user failed</h2>"; }
        $failed_login = '<span class="failed_login">Login Failed</span>';
    }

}

// Asks user for login information
if (!isset($_SESSION['user'])) {

?><!-- @author Jeremiah - Added login box here-->	
<div class = "login_box">
       <div id="login-unauth" class = 'mcontents_box' >
           
        <h1>Login</h1>
        <p>Enter a username and password. If first time, click 'Create Account'. </p>
             <form action="index.php" method="post" class="user_login">
                <ul>
  		   <input class="login" name="user" type="email" placeholder="you@domain.com" required></li>
		   <input class="login" name="passwd" type="password" placeholder="password" required></li>
		</ul>
	 	<ul>
		    <button class="fortune_button" type="submit" name="login_button">LOGIN</button>
	            <?= $failed_login ?>
		</ul>
                <div id="create_account"><a href="create_account.php" target="_new">Create Account</a></div>
             </div>
        </div>
        </div>

<?php

} else {

       ?>
       <div class="login-auth">
       <!--Shows logged in Username, Real Name, and Role-->
             <div class="account_text"><span class="account_header">User:</span> <?php echo $_SESSION['user']; ?><p></div><p>
             <div class="account_text"><span class="account_header">Name:</span> <?php echo $_SESSION['name']; ?><p></div><p>
             <div class="account_text"><span class="account_header">Role:</span> <?php echo $_SESSION['role']; ?><p></div><p>
             </p>
        </div>

        <a href="logout.php">Logout</a>
        </p>

<?php

     if ($_SESSION['role'] == 'admin') {
	include 'adminOption.php';
     } 
     if($_SESSION['role'] == 'user'){
	include 'take_quiz.php';
     }
}

include 'footer.php';

?>
