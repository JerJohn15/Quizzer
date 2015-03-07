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
</div>
